<?php

namespace Tests\Unit;

use App\Helpers\UserHelper;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test] protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'regular']);
        Permission::create(['name' => 'view users']);
    }

    #[Test] public function test_is_super_admin()
    {
        // Arrange
        $superadmin = UserHelper::create_superadmin_user();
        $regular = UserHelper::create_regular_user();

        // Act & Assert
        $this->assertTrue($superadmin->isSuperAdmin(), 'El superadmin ha de retornar true en isSuperAdmin()');
        $this->assertFalse($regular->isSuperAdmin(), 'Un usuari regular ha de retornar false en isSuperAdmin()');
    }

    #[Test] public function test_user_without_permissions_can_see_default_users_page()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get(route('users.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    #[Test] public function test_user_with_permissions_can_see_default_users_page()
    {
        // Arrange
        $user = User::factory()->create();
        $user->givePermissionTo('view users');

        // Act
        $response = $this->actingAs($user)->get(route('users.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    #[Test] public function test_not_logged_users_cannot_see_default_users_page()
    {
        // Act
        $response = $this->get(route('users.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    #[Test] public function test_user_without_permissions_can_see_user_show_page()
    {
        // Arrange
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get(route('users.show', $anotherUser->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
    }

    #[Test] public function test_user_with_permissions_can_see_user_show_page()
    {
        // Arrange
        $user = User::factory()->create();
        $user->givePermissionTo('view users');
        $anotherUser = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get(route('users.show', $anotherUser->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
    }

    #[Test] public function test_not_logged_users_cannot_see_user_show_page()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->get(route('users.show', $user->id));

        // Assert
        $response->assertRedirect(route('login'));
    }
}
