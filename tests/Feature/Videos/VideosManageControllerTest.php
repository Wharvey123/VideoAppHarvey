<?php

namespace Tests\Feature\Videos;

use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideosManageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Crea el permís abans de cada test
        PermissionHelper::create_permissions();
    }

    protected function loginAsVideoManager()
    {
        $user = UserHelper::create_video_manager_user();
        $user->givePermissionTo('manage videos');
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsSuperAdmin()
    {
        $user = UserHelper::create_superadmin_user();
        $user->givePermissionTo('manage videos');
        $this->actingAs($user);
        return $user;
    }

    protected function loginAsRegularUser()
    {
        $user = UserHelper::create_regular_user();
        $this->actingAs($user);
        return $user;
    }

    public function test_user_with_permissions_can_manage_videos()
    {
        $this->loginAsVideoManager();
        $response = $this->get(route('videos.manage'));
        $response->assertStatus(200);
    }

    public function test_regular_users_cannot_manage_videos()
    {
        $this->loginAsRegularUser();
        $response = $this->get(route('videos.manage'));
        $response->assertStatus(403);
    }

    public function test_guest_users_cannot_manage_videos()
    {
        $response = $this->get(route('videos.manage'));
        $response->assertRedirect(route('login'));
    }

    public function test_superadmins_can_manage_videos()
    {
        $this->loginAsSuperAdmin();
        $response = $this->get(route('videos.manage'));
        $response->assertStatus(200);
    }
}
