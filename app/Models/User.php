<?php

namespace App\Models;

use App\Helpers\UserHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Afegit per spatie/laravel-permission

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static create(array $validated)
 * @method static findOrFail($id)
 * @method static where(string $string, mixed $email)
 * @method createPersonalTeam()
 * @method static role(string $string)
 * @property mixed $super_admin
 * @property mixed $name
 * @property mixed $current_team_id
 * @property-read string|null $profile_photo_url
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles; // Trait per gestionar rols i permisos

    /** @var list<string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_team_id',
        'super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Funció de prova (per exemple, pot retornar informació addicional). */
    public function testedBy(): string
    {
        // Exemple: Retorna una cadena de prova o una relació si fos necessari
        return "Tested by " . $this->name;
    }

    /** Comprova si l'usuari és superadmin. */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    // Relació amb l'equip actual
    public function currentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'current_team_id');
    }

    // Relació amb tots els equips de l'usuari
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
    public function add_personal_team()
    {
        return UserHelper::add_personal_team($this);
    }
}
