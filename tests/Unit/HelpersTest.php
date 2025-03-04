<?php

namespace Tests\Unit;

use App\Helpers\UserHelper;
use App\Helpers\VideoHelper;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
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

    #[Test]
    public function crea_els_videos_per_defecte()
    {
        // Creació dels vídeos per defecte
        VideoHelper::createDefaultVideos();

        // Comprovar que s'han creat 3 vídeos
        $this->assertCount(3, Video::all());

        // Verificar que el primer vídeo existeix a la base de dades
        $this->assertDatabaseHas('videos', [
            'title' => 'Introducció a Laravel',
            'description' => 'Un vídeo introductori per aprendre els conceptes bàsics de Laravel.',
            'url' => 'https://www.youtube.com/embed/PGQxIILBb7M',
            'series_id' => 1,
        ]);

        // Verificar que el segon vídeo existeix a la base de dades
        $this->assertDatabaseHas('videos', [
            'title' => 'Controladors a Laravel',
            'description' => 'Aprèn com funcionen els controladors a Laravel i com gestionar les rutes.',
            'url' => 'https://www.youtube.com/embed/0YxgCH2R2bE',
            'series_id' => 1,
        ]);

        // Verificar que el tercer vídeo existeix a la base de dades
        $this->assertDatabaseHas('videos', [
            'title' => 'Models a Laravel',
            'description' => 'Exploració dels models a Laravel i com interactuar amb la base de dades.',
            'url' => 'https://www.youtube.com/embed/f-pWNf0Ht1Y',
            'series_id' => 1,
        ]);
    }
}
