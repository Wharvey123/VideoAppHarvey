<?php

namespace Tests\Unit;

use App\Helpers\UserHelper;
use App\Helpers\VideoHelper;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testCreaElsUsuarisPerDefecte()
    {
        // Creació d'usuaris per defecte
        $user = UserHelper::createDefaultUser();
        $professor = UserHelper::createDefaultProfessor();

        // Verifiquem el Default User
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->current_team_id);
        $this->assertDatabaseHas('teams', [
            'id'            => 1,
            'user_id'       => $user->id,
            'name'          => 'Team 1',
            'personal_team' => true,
        ]);

        // Verifiquem el Default Professor
        $this->assertInstanceOf(User::class, $professor);
        $this->assertEquals(2, $professor->current_team_id);
        $this->assertDatabaseHas('teams', [
            'id'            => 2,
            'user_id'       => $professor->id,
            'name'          => 'Team 2',
            'personal_team' => true,
        ]);
    }

    /**
     * Prepara l'entorn per als tests dels vídeos.
     */
    private function prepareDefaultVideoEnvironment(): void
    {
        // Crear usuaris necessaris si no existeixen
        if (!User::find(1)) {
            UserHelper::createDefaultUser();
        }
        if (!User::find(2)) {
            UserHelper::createDefaultProfessor();
        }
        if (!User::find(3)) {
            User::factory()->create([
                'id'       => 3,
                'name'     => 'Usuari Dummy',
                'email'    => 'dummy@example.com',
                'password' => bcrypt('password'),
            ]);
        }
    }

    #[Test]
    public function testCreaElsVideosPerDefecte()
    {
        $this->prepareDefaultVideoEnvironment();

        // Creació dels vídeos per defecte
        VideoHelper::createDefaultVideos();

        // Comprovar que s'han creat 9 vídeos (3 per cada sèrie)
        $this->assertCount(9, Video::all());

        // Verificar que alguns vídeos existeixen a la base de dades
        $this->assertDatabaseHas('videos', [
            'title'       => 'The Walking Dead - Episodi 1',
            'description' => 'Primer episodi de The Walking Dead.',
            'url'         => 'https://www.youtube.com/embed/sfAc2U20uyg',
            'user_id'     => 1,
            'series_id'   => 1
        ]);

        $this->assertDatabaseHas('videos', [
            'title'       => 'The 100 - Episodi 2',
            'description' => 'Segon episodi de The 100.',
            'url'         => 'https://www.youtube.com/embed/NepXdwVRVtY',
            'user_id'     => 2,
            'series_id'   => 2
        ]);

        $this->assertDatabaseHas('videos', [
            'title'       => 'The Witcher - Episodi 3',
            'description' => 'Tercer episodi de The Witcher.',
            'url'         => 'https://www.youtube.com/embed/SzS8Ao0H6Co',
            'user_id'     => 3,
            'series_id'   => 3
        ]);
    }
}
