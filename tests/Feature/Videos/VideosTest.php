<?php

namespace Tests\Feature\Videos;

use App\Helpers\PermissionHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VideosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create permissions
        PermissionHelper::create_permissions();
        PermissionHelper::create_user_management_permissions();
        PermissionHelper::create_series_management_permissions();
        PermissionHelper::define_gates();

        // Create roles and assign permissions
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->givePermissionTo(Permission::all()); // Grant all permissions

        Role::firstOrCreate(['name' => 'regular']);
    }

    #[Test]
    public function user_without_permissions_can_see_default_videos_page()
    {
        // Arrange: Create a regular user without specific permissions
        $user = User::factory()->create();
        $user->assignRole('regular');

        // Act: Authenticate user and make GET request to videos page
        $response = $this->actingAs($user)->get('/videos');

        // Assert: Verify response is successful (200 OK)
        $response->assertStatus(200)
            ->assertViewIs('videos.index');
    }

    #[Test]
    public function user_with_permissions_can_see_default_videos_page()
    {
        // Arrange: Create user with view videos permission
        $user = User::factory()->create();
        $user->givePermissionTo('manage videos');

        // Act: Authenticate user and make GET request to videos page
        $response = $this->actingAs($user)->get('/videos');

        // Assert: Verify response is successful (200 OK)
        $response->assertStatus(200)
            ->assertViewIs('videos.index');
    }

    #[Test]
    public function not_logged_users_can_see_default_videos_page()
    {
        // Act: Make GET request to videos page without authentication
        $response = $this->get('/videos');

        // Assert: Verify response is successful (200 OK)
        $response->assertStatus(200)
            ->assertViewIs('videos.index');
    }

    #[Test]
    public function superadmin_can_see_videos_management_page()
    {
        // Arrange: Create superadmin user
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        // Act: Access videos management
        $response = $this->actingAs($superadmin)->get('/videos/manage');

        // Assert: Successfully access the page
        $response->assertStatus(200)
            ->assertViewIs('videos.manage.index');
    }

    #[Test]
    public function regular_user_cannot_access_videos_management_page()
    {
        // Arrange: Create regular user
        $user = User::factory()->create();
        $user->assignRole('regular');

        // Act: Attempt to access management page
        $response = $this->actingAs($user)->get('/videos/manage');

        // Assert: Should be forbidden
        $response->assertStatus(403);
    }
}
