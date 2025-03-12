<?php

namespace Database\Seeders;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Helpers\VideoHelper;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos per a la gestió de vídeos
        PermissionHelper::create_permissions();

        // Crear permisos per a la gestió d'usuaris
        PermissionHelper::create_user_management_permissions();

        // Crear usuaris sense assignar current_team_id (es farà a add_personal_team)
        $users = [
            'defaultUser'      => UserHelper::createDefaultUser(),
            'defaultProfessor' => UserHelper::createDefaultProfessor(),
            'superadmin'       => UserHelper::create_superadmin_user(),
            'regularUser'      => UserHelper::create_regular_user(),
            'videoManager'     => UserHelper::create_video_manager_user(),
        ];

        // Assignar equips personals (current_team_id = user id)
        foreach ($users as $user) {
            UserHelper::add_personal_team($user);
        }

        // Assignar permisos segons rol
        $users['defaultProfessor']->givePermissionTo('manage videos');
        $users['superadmin']->givePermissionTo([
            'manage videos',
            'videos.create',
            'videos.edit',
            'videos.delete',
            'manage users',
            'users.create',
            'users.edit',
            'users.delete',
        ]);
        $users['videoManager']->givePermissionTo([
            'manage videos',
            'videos.create',
            'videos.edit',
            'videos.delete',
        ]);

        // Crear vídeos per defecte
        VideoHelper::createDefaultVideos();
    }
}
