<?php

namespace Tests\Feature\Series;

use Tests\TestCase;
use App\Models\User;
use App\Models\Serie;
use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class SeriesManageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users for testing series management.
     *
     * @var User
     */
    private $seriesManager;
    private $regularUser;
    private $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create ALL permissions needed for the application
        PermissionHelper::create_permissions(); // Creates "manage videos" and related
        PermissionHelper::create_series_management_permissions(); // Creates series permissions
        PermissionHelper::create_user_management_permissions(); // If needed for other tests

        // Define gates AFTER permissions exist
        PermissionHelper::define_gates();

        // Create a series manager user with the required series permissions.
        $this->seriesManager = UserHelper::create_video_manager_user();
        $this->seriesManager->givePermissionTo('manage series');
        $this->seriesManager->givePermissionTo('series.create');
        $this->seriesManager->givePermissionTo('series.edit');
        $this->seriesManager->givePermissionTo('series.delete');

        // Create a regular user.
        $this->regularUser = UserHelper::create_regular_user();

        // Create a superadmin user with series permissions.
        $this->superAdmin = UserHelper::create_superadmin_user();
        $this->superAdmin->givePermissionTo('manage series');
        $this->superAdmin->givePermissionTo('series.create');
        $this->superAdmin->givePermissionTo('series.edit');
        $this->superAdmin->givePermissionTo('series.delete');
    }

    protected function loginAsSeriesManager()
    {
        $this->actingAs($this->seriesManager);
    }

    protected function loginAsSuperAdmin()
    {
        $this->actingAs($this->superAdmin);
    }

    protected function loginAsRegularUser()
    {
        $this->actingAs($this->regularUser);
    }

    public function test_user_with_permissions_can_see_add_series()
    {
        $this->loginAsSeriesManager();
        $response = $this->get(route('series.manage.create'));
        $response->assertStatus(200);
        $response->assertSee('Crear Nova Sèrie');
    }

    public function test_user_with_permissions_can_store_series()
    {
        $this->loginAsSeriesManager();

        // Estableix la URL de redirecció esperada a la sessió
        session(['series_create_redirect' => route('series.manage.index')]);

        $serieData = [
            'title'          => 'Nova Sèrie',
            'description'    => 'Descripció de la nova sèrie.',
            'image'          => 'http://example.com/image.jpg',
        ];

        $response = $this->post(route('series.manage.store'), $serieData);

        $response->assertRedirect(route('series.manage.index'));
        $this->assertDatabaseHas('series', ['title' => 'Nova Sèrie']);
    }

    public function test_user_with_permissions_can_destroy_series()
    {
        $this->loginAsSeriesManager();
        $serie = Serie::factory()->create();
        // IMPORTANT: Utilitzem la ruta 'series.manage.destroy' perquè és la que té el mètode DELETE.
        $response = $this->delete(route('series.manage.destroy', $serie->id));
        $response->assertRedirect(route('series.manage.index'));
        $this->assertDatabaseMissing('series', ['id' => $serie->id]);
    }

    public function test_user_without_permissions_cannot_destroy_series()
    {
        $this->loginAsRegularUser();
        $serie = Serie::factory()->create();
        // Utilitzem la ruta correcta amb DELETE.
        $response = $this->delete(route('series.manage.destroy', $serie->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('series', ['id' => $serie->id]);
    }

    public function test_user_with_permissions_can_see_edit_series()
    {
        $this->loginAsSeriesManager();
        $serie = Serie::factory()->create(['title' => 'Sèrie per Editar']);
        $response = $this->get(route('series.manage.edit', $serie->id));
        $response->assertStatus(200);
        $response->assertSee('Editar'); // Comprova que la vista d'edició contingui aquest text.
        $response->assertSee('Sèrie per Editar');
    }

    public function test_user_without_permissions_cannot_see_edit_series()
    {
        $this->loginAsRegularUser();
        $serie = Serie::factory()->create();
        $response = $this->get(route('series.manage.edit', $serie->id));
        $response->assertStatus(403);
    }

    public function test_user_with_permissions_can_update_series()
    {
        $this->loginAsSeriesManager();
        $serie = Serie::factory()->create([
            'title'          => 'Sèrie Antiga',
            'description'    => 'Antiga descripció',
            'user_name'      => 'Usuari Antic',
            'user_photo_url' => 'http://example.com/old.jpg',
            'published_at'   => Carbon::now()->toDateTimeString(),
        ]);
        $updateData = [
            'title'          => 'Sèrie Actualitzada',
            'description'    => 'Descripció actualitzada',
            'image'          => 'http://example.com/updated.jpg',
            // La data de publicació es sobreescriu amb la data actual dins del controlador.
        ];
        $response = $this->put(route('series.manage.update', $serie->id), $updateData);
        $response->assertRedirect(route('series.manage.index'));
        $this->assertDatabaseHas('series', ['id' => $serie->id, 'title' => 'Sèrie Actualitzada']);
    }

    public function test_user_without_permissions_cannot_update_series()
    {
        $this->loginAsRegularUser();
        $serie = Serie::factory()->create([
            'title'          => 'Sèrie Antiga',
            'description'    => 'Antiga descripció',
            'user_name'      => 'Usuari Antic',
            'user_photo_url' => 'http://example.com/old.jpg',
            'published_at'   => Carbon::now()->toDateTimeString(),
        ]);
        $updateData = [
            'title'          => 'Sèrie Actualitzada',
            'description'    => 'Descripció actualitzada',
            'image'          => 'http://example.com/updated.jpg',
        ];
        $response = $this->put(route('series.manage.update', $serie->id), $updateData);
        $response->assertStatus(403);
        $this->assertDatabaseHas('series', ['id' => $serie->id, 'title' => 'Sèrie Antiga']);
    }

    public function test_user_with_permissions_can_manage_series()
    {
        $this->loginAsSeriesManager();
        $serie = Serie::factory()->create();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(200);
        $response->assertSee($serie->title);
    }

    public function test_regular_users_cannot_manage_series()
    {
        $this->loginAsRegularUser();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(403);
    }

    public function test_guest_users_cannot_manage_series()
    {
        $response = $this->get(route('series.manage.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_videomanagers_can_manage_series()
    {
        $this->loginAsSeriesManager();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(200);
    }

    public function test_superadmins_can_manage_series()
    {
        $this->loginAsSuperAdmin();
        $response = $this->get(route('series.manage.index'));
        $response->assertStatus(200);
    }
}
