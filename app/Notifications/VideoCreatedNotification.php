<?php

namespace App\Notifications;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class VideoCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
        Log::info('VideoCreatedNotification instanciada per vídeo ID: ' . $video->id);
    }

    public function via($notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Nou vídeo creat: :title', ['title' => $this->video->title]))
            ->greeting(__('Hola :name,', ['name' => $notifiable->name ?? '']))
            ->line(__('S\'ha creat un nou vídeo amb el títol: :title', ['title' => $this->video->title]))
            ->action(__('Veure vídeo'), url("/video/{$this->video->id}"))
            ->line(__('Gràcies per utilitzar la nostra aplicació!'));
    }

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
