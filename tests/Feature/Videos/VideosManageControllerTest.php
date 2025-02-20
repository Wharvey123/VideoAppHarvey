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

    #[Test] public function loginAsVideoManager()
    {
        PermissionHelper::create_permissions();
        // Arrange: Crear un usuari amb permís de 'manage videos'
        $user = UserHelper::create_video_manager_user();
        $user->givePermissionTo('manage videos');
        $user->givePermissionTo('videos.create');
        $user->givePermissionTo('videos.edit');
        $user->givePermissionTo('videos.delete');

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }

    #[Test] public function loginAsSuperAdmin()
    {
        // Arrange: Crear un usuari amb rol de 'superadmin'
        PermissionHelper::create_permissions();
        $user = UserHelper::create_superadmin_user();
        $user->givePermissionTo('manage videos');
        $user->givePermissionTo('videos.create');
        $user->givePermissionTo('videos.edit');
        $user->givePermissionTo('videos.delete');

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }

    #[Test] public function loginAsRegularUser()
    {
        // Arrange: Crear un usuari sense permisos especials
        $user = User::factory()->create();

        // Act: Autenticar l'usuari
        $this->actingAs($user);

        // Assert: Verificar que l'usuari està autenticat
        $this->assertAuthenticatedAs($user);
    }

    #[Test]  public function user_with_permissions_can_see_add_videos()
    {
        // Arrange: Autenticar com a usuari amb permís
        $this->loginAsVideoManager();

        // Act: Accedir a la pàgina de creació de vídeos
        $response = $this->get(route('videos.manage.create'));

        // Assert: Comprovar que la resposta és exitosa
        $response->assertStatus(200);
    }

    #[Test]  public function user_without_videos_manage_create_cannot_see_add_videos()
    {
        // Arrange: Autenticar com a usuari sense permís
        $this->loginAsRegularUser();

        // Act: Accedir a la pàgina de creació de vídeos
        $response = $this->get(route('videos.manage.create'));

        // Assert: Comprovar que l'accés és denegat
        $response->assertStatus(403);
    }

    #[Test]  public function user_with_permissions_can_store_videos()
    {
        // Arrange: Autenticar com a usuari amb permís
        $this->loginAsVideoManager();

        // Act: Enviar una petició POST per crear un vídeo
        $response = $this->post(route('videos.manage.store'), [
            'title' => 'Vídeo',
            'description' => 'Description',
            'url' => 'https://www.youtube.com/embed/gKz5NZNs15g',
            'published_at' => null,
            'series_id' => null]
        );

        // Assert: Comprovar que el vídeo s'ha creat i redirigeix correctament
        $response->assertRedirect(route('videos.manage.index'));
        $this->assertDatabaseHas('videos', ['title' => 'Vídeo']);
    }

    #[Test]  public function user_without_permissions_cannot_store_videos()
    {
        // Arrange: Autenticar com a usuari sense permís
        $this->loginAsRegularUser();

        // Act: Enviar una petició POST per crear un vídeo
        $response = $this->post(route('videos.manage.store'), [
                'title' => 'Vídeo 2',
                'description' => 'Description 2',
                'url' => 'https://www.youtube.com/embed/gKz5NZNs15g',
                'published_at' => null,
                'series_id' => null]
        );

        // Assert: Comprovar que l'accés és denegat i el vídeo no s'ha creat
        $response->assertStatus(403);
        $this->assertDatabaseMissing('videos', ['title' => 'Nou Vídeo']);
    }

    #[Test]  public function user_with_permissions_can_destroy_videos()
    {
        // Arrange: Autenticar com a usuari amb permís i crear un vídeo
        $this->loginAsVideoManager();
        $video = Video::factory()->create();

        // Act: Enviar una petició DELETE per eliminar el vídeo
        $response = $this->delete(route('videos.manage.destroy', $video->id));

        // Assert: Comprovar que el vídeo s'ha eliminat i redirigeix correctament
        $response->assertRedirect(route('videos.manage.index'));
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }

    #[Test] public function user_without_permissions_cannot_destroy_videos()
    {
        // Arrange: Autenticar com a usuari sense permís i crear un vídeo
        $this->loginAsRegularUser();
        $video = Video::factory()->create();

        // Act: Enviar una petició DELETE per eliminar el vídeo
        $response = $this->delete(route('videos.manage.destroy', $video->id));

        // Assert: Comprovar que l'accés és denegat i el vídeo no s'ha eliminat
        $response->assertStatus(403);
        $this->assertDatabaseHas('videos', ['id' => $video->id]);
    }

    #[Test] public function user_with_permissions_can_see_edit_videos()
    {
        // Arrange: Autenticar com a usuari amb permís i crear un vídeo
        $this->loginAsVideoManager();
        $video = Video::factory()->create();

        // Act: Accedir a la pàgina d'edició del vídeo
        $response = $this->get(route('videos.manage.edit', $video->id));

        // Assert: Comprovar que la resposta és exitosa
        $response->assertStatus(200);
    }

    #[Test] public function user_without_permissions_cannot_see_edit_videos()
    {
        // Arrange: Autenticar com a usuari sense permís i crear un vídeo
        $this->loginAsRegularUser();
        $video = Video::factory()->create();

        // Act: Accedir a la pàgina d'edició del vídeo
        $response = $this->get(route('videos.manage.edit', $video->id));

        // Assert: Comprovar que l'accés és denegat
        $response->assertStatus(403);
    }

    #[Test] public function user_with_permissions_can_update_videos()
    {
        // Arrange: Autenticar com a usuari amb permís
        $this->loginAsVideoManager();

        // Crear un vídeo existent per actualitzar
        $video = Video::create([
            'title' => 'Vídeo existent',
            'description' => 'Descripció existent',
            'url' => 'https://www.youtube.com/embed/existent',
            'published_at' => null,
            'series_id' => null
        ]);

        // Dades per actualitzar
        $updatedData = [
            'title' => 'Títol actualitzat',
            'description' => 'Descripció actualitzada',
            'url' => 'https://www.youtube.com/embed/actualitzat',
            'published_at' => null,
            'series_id' => null
        ];

        // Act: Enviar una sol·licitud PUT per actualitzar el vídeo
        $response = $this->put(route('videos.manage.update', ['id' => $video->id]), $updatedData);

        // Assert: Comprovar que la resposta és una redirecció a la pàgina d'índex de vídeos
        $response->assertRedirect(route('videos.manage.index'));

        // Assert: Comprovar que les dades del vídeo s'han actualitzat a la base de dades
        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'title' => 'Títol actualitzat',
            'description' => 'Descripció actualitzada',
            'url' => 'https://www.youtube.com/embed/actualitzat',
            'published_at' => null,
            'series_id' => null
        ]);
    }

    #[Test] public function user_without_permissions_cannot_update_videos()
    {
        // Arrange
        $this->loginAsRegularUser();
        $videoData = ['title' => 'New Title', 'description' => 'Updated description'];

        // Act: Intentar actualitzar el vídeo
        $response = $this->put(route('videos.manage.update', ['id' => 1]), $videoData);

        // Assert: Verificar que l'usuari rep un error 403
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_permissions_can_manage_videos()
    {
        // Arrange
        $this->loginAsVideoManager();

        // Crear tres vídeos
        Video::factory()->count(3)->create();

        // Act: Accedir a la pàgina de gestió de vídeos
        $response = $this->get(route('videos.manage.index'));

        // Assert: Verificar que l'usuari pot accedir a la pàgina
        $response->assertStatus(200);

        // Verificar que els vídeos es mostren a la pàgina
        $response->assertSee(Video::first()->title);
        $response->assertSee(Video::skip(1)->first()->title);
        $response->assertSee(Video::skip(2)->first()->title);
    }

    #[Test] public function regular_users_cannot_manage_videos()
    {
        // Arrange
        $this->loginAsRegularUser();

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

    #[Test] public function superadmins_can_manage_videos()
    {
        // Arrange
        $this->loginAsSuperAdmin();

        // Act: Accedir a la pàgina de gestió de vídeos
        $response = $this->get(route('videos.manage.index'));

        // Assert: Verificar que l'usuari pot accedir a la pàgina
        $response->assertStatus(200);
    }
}
