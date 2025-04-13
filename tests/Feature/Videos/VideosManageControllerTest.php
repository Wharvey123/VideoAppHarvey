<?php

namespace Tests\Feature\Videos;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VideosManageControllerTest extends TestCase
{
    use RefreshDatabase;

    private $videoManager;
    private $regularUser;
    private $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        PermissionHelper::create_permissions();
        PermissionHelper::create_user_management_permissions();
        PermissionHelper::create_series_management_permissions();
        PermissionHelper::define_gates();

        $this->videoManager = UserHelper::create_video_manager_user();
        $this->videoManager->givePermissionTo('manage videos');
        $this->videoManager->givePermissionTo('videos.create');
        $this->videoManager->givePermissionTo('videos.edit');
        $this->videoManager->givePermissionTo('videos.delete');

        $this->regularUser = UserHelper::create_regular_user();
        $this->superAdmin = UserHelper::create_superadmin_user();
    }

    #[Test]
    public function user_with_permissions_can_see_add_videos()
    {
        $this->actingAs($this->videoManager);
        $response = $this->get(route('videos.manage.create'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_without_videos_manage_create_cannot_see_add_videos()
    {
        $this->actingAs($this->regularUser);
        $response = $this->get(route('videos.manage.create'));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_store_videos()
    {
        $this->actingAs($this->videoManager);
        $response = $this->post(route('videos.manage.store'), [
            'title' => 'Vídeo',
            'description' => 'Descripció',
            'url' => 'https://www.youtube.com/embed/gKz5NZNs15g',
            'user_id' => $this->videoManager->id
        ]);
        $response->assertRedirect(route('videos.manage.index'));
        $this->assertDatabaseHas('videos', ['title' => 'Vídeo']);
    }

    #[Test]
    public function user_without_permissions_cannot_store_videos()
    {
        $this->actingAs($this->regularUser);
        $response = $this->post(route('videos.manage.store'), [
            'title' => 'Vídeo 2',
            'description' => 'Descripció 2',
            'url' => 'https://www.youtube.com/embed/gKz5NZNs15g',
            'user_id' => $this->regularUser->id
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('videos', ['title' => 'Vídeo 2']);
    }

    #[Test]
    public function user_with_permissions_can_destroy_videos()
    {
        $this->actingAs($this->videoManager);
        $video = Video::factory()->create();
        $response = $this->delete(route('videos.manage.destroy', $video->id));
        $response->assertRedirect(route('videos.manage.index'));
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    #[Test]
    public function user_without_permissions_cannot_destroy_videos()
    {
        $this->actingAs($this->regularUser);
        $video = Video::factory()->create();
        $response = $this->delete(route('videos.manage.destroy', $video->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('videos', ['id' => $video->id]);
    }

    #[Test]
    public function user_with_permissions_can_see_edit_videos()
    {
        $this->actingAs($this->videoManager);
        $video = Video::factory()->create();
        $response = $this->get(route('videos.manage.edit', $video->id));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_without_permissions_cannot_see_edit_videos()
    {
        $this->actingAs($this->regularUser);
        $video = Video::factory()->create();
        $response = $this->get(route('videos.manage.edit', $video->id));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_update_videos()
    {
        $this->actingAs($this->videoManager);
        $video = Video::factory()->create();
        $updatedData = [
            'title' => 'Títol actualitzat',
            'description' => 'Descripció actualitzada',
            'url' => 'https://www.youtube.com/embed/actualitzat',
            'user_id' => $this->videoManager->id
        ];
        $response = $this->put(route('videos.manage.update', $video->id), $updatedData);
        $response->assertRedirect(route('videos.manage.index'));
        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => 'Títol actualitzat',
            'description' => 'Descripció actualitzada',
            'url' => 'https://www.youtube.com/embed/actualitzat',
            'user_id' => $this->videoManager->id
        ]);
    }

    #[Test]
    public function user_without_permissions_cannot_update_videos()
    {
        $this->actingAs($this->regularUser);
        $video = Video::factory()->create();
        $updatedData = [
            'title' => 'Títol actualitzat',
            'description' => 'Descripció actualitzada',
            'url' => 'https://www.youtube.com/embed/actualitzat',
            'user_id' => $this->regularUser->id
        ];
        $response = $this->put(route('videos.manage.update', $video->id), $updatedData);
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_manage_videos()
    {
        // Arrange
        $this->actingAs($this->videoManager);

        // Crear tres vídeos
        Video::factory()->count(3)->create();

        // Act: Accedir a la pàgina de gestió de vídeos
        $response = $this->get(route('videos.manage.index'));

        // Assert: Verificar que l'usuari pot accedir a la pàgina
        $response->assertStatus(200);

        // Verificar que els vídeos es mostren a la pàgina
        $videos = Video::all();
        foreach ($videos as $video) {
            $response->assertSee($video->title);
        }
    }

    #[Test]
    public function regular_users_cannot_manage_videos()
    {
        // Arrange
        $this->actingAs($this->regularUser);

        // Act: Intentar accedir a la pàgina de gestió de vídeos
        $response = $this->get(route('videos.manage.index'));

        // Assert: Verificar que l'usuari rep un error 403
        $response->assertStatus(403);
    }

    #[Test] public function guest_users_cannot_manage_videos()
    {
        // Act: Intentar accedir a la pàgina de gestió de vídeos sense autenticar
        $response = $this->get(route('videos.manage.index'));

        // Assert: Verificar que l'usuari és redirigit a la pàgina de login
        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function superadmins_can_manage_videos()
    {
        // Arrange
        $this->actingAs($this->superAdmin);

        // Act: Accedir a la pàgina de gestió de vídeos
        $response = $this->get(route('videos.manage.index'));

        // Assert: Verificar que l'usuari pot accedir a la pàgina
        $response->assertStatus(200);
    }
}
