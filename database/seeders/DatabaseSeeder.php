<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Helpers\UserHelper;
use App\Helpers\VideoHelper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the default user and professor
        UserHelper::createDefaultUser();
        UserHelper::createDefaultProfessor();

        // Crea vídeos per defecte
        VideoHelper::createDefaultVideos();
    }
}
