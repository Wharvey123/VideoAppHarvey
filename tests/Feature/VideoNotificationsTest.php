<?php

namespace Tests\Feature;

use App\Events\VideoCreated;
use App\Models\User;
use App\Notifications\VideoCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class VideoNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Crear permisos i rols necessaris
        $permissions = ['videos.create', 'manage videos', 'series.create', 'manage series'];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->syncPermissions($permissions);

        // Definir portes d'accés (com a PermissionHelper)
        \Illuminate\Support\Facades\Gate::define('videos.create', fn($user) =>
            $user->hasPermissionTo('videos.create') || $user->isSuperAdmin()
        );

        // Configurar el driver de broadcasting a 'null' per als tests
        config(['broadcasting.default' => 'null']);
    }

    #[Test]
    public function video_created_event_is_dispatched()
    {
        // Arrange
        Event::fake([VideoCreated::class]);
        $user = User::factory()->create(['super_admin' => true]);
        $user->assignRole('superadmin');
        $this->actingAs($user);

        // Act
        $videoData = [
            'title' => 'Test Video',
            'description' => 'This is a test video description',
            'url' => 'https://example.com/video.mp4',
        ];
        $response = $this->post(route('videos.manage.store'), $videoData);

        // Assert
        $response->assertRedirect();
        Event::assertDispatched(VideoCreated::class, function ($event) use ($videoData) {
            return $event->video->title === $videoData['title'];
        });
    }

    #[Test]
    public function push_notification_is_sent_when_video_is_created()
    {
        // Fake notifications so we can assert without sending real ones
        Notification::fake();

        // Arrange
        $superAdmin = User::factory()->create(['super_admin' => true]);
        $superAdmin->assignRole('superadmin');
        $user = User::factory()->create(['super_admin' => false]);
        $this->actingAs($superAdmin);

        // Act
        $videoData = [
            'title'       => 'Test Video',
            'description' => 'This is a test video description',
            'url'         => 'https://example.com/video.mp4',
        ];
        $response = $this->post(route('videos.manage.store'), $videoData);

        // Assert redirect
        $response->assertRedirect();

        // Assert notification was (falsely) sent to the super admin
        Notification::assertSentTo(
            [$superAdmin],
            VideoCreatedNotification::class,
            fn($notification, $channels) =>
                in_array('broadcast', $channels) &&
                $notification->video->title === $videoData['title']
        );

        // Assert no notification sent to the other user
        Notification::assertNotSentTo([$user], VideoCreatedNotification::class);
        $this->assertTrue(true);
    }
}
