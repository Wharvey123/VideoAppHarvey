<?php

namespace App\Helpers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserHelper
{
    public static function createDefaultUser()
    {
        $userData = config('helpers.default_user');

        // Crear o trobar l'equip amb l'ID especificat
        $team = Team::firstOrCreate(
            ['id' => $userData['team_id']], // Usar l'ID de l'equip de la configuració
            ['name' => 'Team ' . $userData['team_id'], 'user_id' => $userData['user_id'], 'personal_team' => true]
        );

        // Crear l'usuari per defecte i assignar-li l'equip
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'current_team_id' => 1,
        ]);

        // Actualitzar el propietari de l'equip
        $team->update(['user_id' => $user->id]);

        return $user;
    }

    public static function createDefaultProfessor()
    {
        $professorData = config('helpers.default_professor');

        // Crear o trobar l'equip amb l'ID especificat
        $team = Team::firstOrCreate(
            ['id' => $professorData['team_id']], // Usar l'ID de l'equip de la configuració
            ['name' => 'Team ' . $professorData['team_id'], 'user_id' => $professorData['user_id'], 'personal_team' => true]
        );

        // Crear el professor per defecte i assignar-li l'equip
        $professor = User::create([
            'name' => $professorData['name'],
            'email' => $professorData['email'],
            'password' => Hash::make($professorData['password']),
            'current_team_id' => 2,
        ]);

        // Actualitzar el propietari de l'equip
        $team->update(['user_id' => $professor->id]);

        return $professor;
    }
}
