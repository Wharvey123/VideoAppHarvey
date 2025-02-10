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
        // Crear permisos
        PermissionHelper::create_permissions();

        // Crear usuaris sense assignar current_team_id (aquest es farà a add_personal_team)
        $defaultUser      = UserHelper::createDefaultUser();
        $defaultProfessor = UserHelper::createDefaultProfessor();
        $superadmin       = UserHelper::create_superadmin_user();
        $regularUser      = UserHelper::create_regular_user();
        $videoManager     = UserHelper::create_video_manager_user();

        // Assignar equips personals i, per tant, current_team_id = user id
        UserHelper::add_personal_team($defaultUser);
        UserHelper::add_personal_team($defaultProfessor);
        UserHelper::add_personal_team($superadmin);
        UserHelper::add_personal_team($regularUser);
        UserHelper::add_personal_team($videoManager);

        // Assignar permisos segons rol (exemple)
        $defaultProfessor->givePermissionTo('manage videos');
        $superadmin->givePermissionTo('manage videos');
        $videoManager->givePermissionTo('manage videos');

        // Crear vídeos per defecte
        VideoHelper::createDefaultVideos();
    }
}
