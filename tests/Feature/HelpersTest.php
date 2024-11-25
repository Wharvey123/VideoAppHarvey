<?php

namespace Tests\Feature;

use App\Helpers\UserHelper;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test; // Add this if using PHP 8.1+

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    #[Test] // Use the PHPUnit attribute instead of @test
    public function crea_els_usuaris_per_defecte()
    {
        // Creació d'usuaris
        $user = UserHelper::createDefaultUser();
        $professor = UserHelper::createDefaultProfessor();

        // Default User
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->current_team_id);
        // Verifiquem que l'usuari té l'equip 1
        $this->assertDatabaseHas('teams', [
            'id' => 1,
            'user_id' => 1,
            'name' => 'Team 1',
            'personal_team' => true,
        ]);

        // Default Professor
        $this->assertInstanceOf(User::class, $professor);
        $this->assertEquals(2, $professor->current_team_id);
        // Verifiquem que el professor té l'equip 2
        $this->assertDatabaseHas('teams', [
            'id' => 2,
            'user_id' => 2,
            'name' => 'Team 2',
            'personal_team' => true,
        ]);
    }
}
