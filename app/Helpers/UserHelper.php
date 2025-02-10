<?php

namespace App\Helpers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserHelper
{
    public static function createDefaultUser()
    {
        // Obtenció de dades de l'usuari per defecte des de la configuració
        $userData = config('helpers.default_user');

        // Creació o obtenció del primer equip (Team) amb l'ID especificat
        $team = Team::firstOrCreate(
            ['id' => $userData['team_id']],
            ['name' => 'Team ' . $userData['team_id'], 'user_id' => $userData['user_id'], 'personal_team' => true]
        );

        // Actualització o creació d'un usuari amb l'email especificat
        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'],
                'password' => Hash::make($userData['password']),
                'current_team_id' => $userData['team_id'],
            ]
        );

        // Actualització de l'equip amb el nou ID d'usuari
        $team->update(['user_id' => $user->id]);

        return $user;
    }

    public static function createDefaultProfessor()
    {
        // Obtenció de dades del professor per defecte des de la configuració
        $professorData = config('helpers.default_professor');

        // Creació o obtenció del primer equip (Team) amb l'ID especificat
        $team = Team::firstOrCreate(
            ['id' => $professorData['team_id']],
            ['name' => 'Team ' . $professorData['team_id'], 'user_id' => $professorData['user_id'], 'personal_team' => true]
        );

        // Actualització o creació d'un professor amb l'email especificat
        $professor = User::updateOrCreate(
            ['email' => $professorData['email']],
            [
                'name' => $professorData['name'],
                'password' => Hash::make($professorData['password']),
                'current_team_id' => $professorData['team_id'],
                'super_admin' => true,
            ]
        );

        // Actualització de l'equip amb el nou ID de professor
        $team->update(['user_id' => $professor->id]);

        // Comprovar si el rol 'superadmin' existeix, si no, crear-lo
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);

        // Assignar el rol de 'superadmin' al professor
        $professor->assignRole($superadminRole);

        return $professor;
    }

    public static function add_personal_team(User $user)
    {
        // Creació o obtenció de l'equip personal (personal_team) per a un usuari
        return Team::firstOrCreate(
            ['user_id' => $user->id, 'personal_team' => true],
            ['name' => $user->name . "'s Team"]
        );
    }

    public static function create_superadmin_user()
    {
        // Actualització o creació d'un usuari superadmin amb tots els permisos
        return User::updateOrCreate(
            ['email' => 'superadmin@videosapp.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('123456789'),
                'current_team_id' => 3,
                'super_admin' => true,
            ]
        );
    }

    public static function create_regular_user()
    {
        // Actualització o creació d'un usuari regular
        return User::updateOrCreate(
            ['email' => 'regular@videosapp.com'],
            [
                'name' => 'Regular',
                'password' => Hash::make('123456789'),
                'current_team_id' => 4,
            ]
        );    }

    public static function create_video_manager_user()
    {
        // Actualització o creació d'un usuari gestor de vídeos
        return User::updateOrCreate(
            ['email' => 'videosmanager@videosapp.com'],
            [
                'name' => 'Video Manager',
                'password' => Hash::make('123456789'),
                'current_team_id' => 5,
            ]
        );
    }

}
