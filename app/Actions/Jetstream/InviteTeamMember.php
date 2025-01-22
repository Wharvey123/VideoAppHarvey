<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Mail\TeamInvitation; // Importació de la classe Mail de Jetstream
use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation; // Importació del model correcte per a la invitació
use Laravel\Jetstream\Rules\Role;

class InviteTeamMember implements InvitesTeamMembers
{
    /**
     * Convida un nou membre a l'equip.
     */
    public function invite(User $user, Team $team, string $email, ?string $role = null): void
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $email, $role);

        InvitingTeamMember::dispatch($team, $email, $role);

        // Crear una instància de TeamInvitation de Jetstream
        $invitation = new JetstreamTeamInvitation([
            'email' => $email,
            'role' => $role,
        ]);

        // Guardar la invitació al model TeamInvitation relacionat amb l'equip
        $team->teamInvitations()->save($invitation);

        // Enviar la invitació per correu
        Mail::to($email)->send(new TeamInvitation($invitation));
    }

    /**
     * Validar la operació d'invitació.
     */
    protected function validate(Team $team, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules($team), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $email)
        )->validateWithBag('addTeamMember');
    }

    /**
     * Obtenir les regles de validació per a convidar un membre de l'equip.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(Team $team): array
    {
        return array_filter([
            'email' => [
                'required', 'email',
                Rule::unique(Jetstream::teamInvitationModel())->where(function (Builder $query) use ($team) {
                    $query->where('team_id', $team->id);
                }),
            ],
            'role' => Jetstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
        ]);
    }

    /**
     * Assegura't que l'usuari no estigui ja en l'equip.
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, string $email): Closure
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
