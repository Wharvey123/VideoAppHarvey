<?php

namespace App\Notifications;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class VideoCreatedNotification extends Notification
{
    use Queueable;

    public Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function via($notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nou vídeo creat')
            ->line("S'ha creat un nou vídeo: {$this->video->title}")
            ->action('Veure vídeo', url("/video/{$this->video->id}"));
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'    => $this->video->id,
            'title' => $this->video->title,
        ]);
    }
}
