<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Helpers\UserHelper;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();
        //User::factory()->withPersonalTeam()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);

        // Create the default user and professor
        UserHelper::createDefaultUser();
        UserHelper::createDefaultProfessor();
    }
}
