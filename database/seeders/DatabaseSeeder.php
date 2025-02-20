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
        // Crear permisos (inclou 'videos.create', 'videos.edit' i 'videos.delete')
        PermissionHelper::create_permissions();

        // Crear usuaris sense assignar current_team_id (es farà a add_personal_team)
        $defaultUser      = UserHelper::createDefaultUser();
        $defaultProfessor = UserHelper::createDefaultProfessor();
        $superadmin       = UserHelper::create_superadmin_user();
        $regularUser      = UserHelper::create_regular_user();
        $videoManager     = UserHelper::create_video_manager_user();

        // Assignar equips personals (current_team_id = user id)
        UserHelper::add_personal_team($defaultUser);
        UserHelper::add_personal_team($defaultProfessor);
        UserHelper::add_personal_team($superadmin);
        UserHelper::add_personal_team($regularUser);
        UserHelper::add_personal_team($videoManager);

        // Assignar permisos segons rol:
        // Per exemple, el professor i el superadmin tenen 'manage videos'
        $defaultProfessor->givePermissionTo('manage videos');
        $superadmin->givePermissionTo('manage videos');

        // Al Video Manager li assignem els permisos específics per al CRUD:
        $videoManager->givePermissionTo('manage videos');
        $videoManager->givePermissionTo('videos.create');
        $videoManager->givePermissionTo('videos.edit');
        $videoManager->givePermissionTo('videos.delete');

        // Al Super Admin li assignem els permisos específics per al CRUD:
        $superadmin->givePermissionTo('manage videos');
        $superadmin->givePermissionTo('videos.create');
        $superadmin->givePermissionTo('videos.edit');
        $superadmin->givePermissionTo('videos.delete');

        // Crear vídeos per defecte
        VideoHelper::createDefaultVideos();
    }
}
