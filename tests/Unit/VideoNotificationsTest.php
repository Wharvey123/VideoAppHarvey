<?php

namespace Tests\Unit;

use App\Events\VideoCreated;
use App\Helpers\PermissionHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Models\Video;
use App\Notifications\VideoCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VideoNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private User $videoManager;
    private User $admin;
    private array $validData = [
        'title'       => 'Test Video',
        'description' => 'A description for testing.',
        'url'         => 'https://www.youtube.com/embed/test12345',
        'series_id'   => null,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Crear permisos i gates
        PermissionHelper::create_permissions();
        PermissionHelper::create_series_management_permissions();
        PermissionHelper::create_user_management_permissions();
        PermissionHelper::define_gates();

        // Crear users
        $this->videoManager = UserHelper::create_video_manager_user();
        $this->videoManager->givePermissionTo('videos.create');
        $this->videoManager->givePermissionTo('manage videos');

        // Crear super-admin per boolean super_admin
        $this->admin = User::factory()->create([
            'super_admin' => true,
        ]);
    }

    #[Test]
    public function test_video_created_event_is_dispatched()
    {
        Event::fake();

        $this->actingAs($this->videoManager)
            ->post(route('videos.manage.store'), $this->validData);

        Event::assertDispatched(VideoCreated::class, function (VideoCreated $event) {
            return $event->video->title === 'Test Video';
        });
    }

    #[Test]
    public function test_notifications_are_sent_when_video_is_created()
    {
        Notification::fake();

        $this->actingAs($this->videoManager)
            ->post(route('videos.manage.store'), $this->validData);

        $video = Video::where('title', 'Test Video')->firstOrFail();

        Notification::assertSentTo(
            [$this->admin],
            VideoCreatedNotification::class,
            function ($notification, $channels) use ($video) {
                // Ha d'enviar-se per mail i broadcast
                return in_array('broadcast', $channels, true)
                    && in_array('mail', $channels, true)
                    && $notification->video->id === $video->id;
            }
        );
    }
}
