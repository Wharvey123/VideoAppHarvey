<?php

namespace App\Notifications;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class VideoCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public Video $video;

    public function __construct(Video $video)
    { $this->video = $video; }

    public function via($notifiable): array
    { return ['mail', 'broadcast', 'database']; }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'video_id'   => $this->video->id,
            'title'      => $this->video->title,
            'created_at' => $this->video->created_at->toIso8601String(),
        ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'video_id' => $this->video->id,
            'title'    => $this->video->title,
            'url'      => url("/video/{$this->video->id}"),
        ];
    }
}
