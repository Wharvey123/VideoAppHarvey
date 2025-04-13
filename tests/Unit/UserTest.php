<?php

namespace Tests\Unit;

use App\Helpers\PermissionHelper;
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

    protected function setUp(): void
    {
        parent::setUp();
        PermissionHelper::create_permissions();
        PermissionHelper::create_series_management_permissions();
        PermissionHelper::create_user_management_permissions();
        PermissionHelper::define_gates();
    }

    #[Test]
    public function test_is_super_admin()
    {
        $superadmin = UserHelper::create_superadmin_user();
        $regular = UserHelper::create_regular_user();

        $this->assertTrue($superadmin->isSuperAdmin());
        $this->assertFalse($regular->isSuperAdmin());
    }

    #[Test]
    public function test_user_with_permissions_can_see_default_users_page()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view users');
        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function test_user_without_permissions_can_see_user_show_page()
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.show', $anotherUser));
        $response->assertStatus(200); // Or 403 if you want to restrict access
    }

    #[Test]
    public function test_user_with_permissions_can_see_user_show_page()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view users');
        $anotherUser = User::factory()->create();
        $response = $this->actingAs($user)->get(route('users.show', $anotherUser));
        $response->assertStatus(200);
    }
}
