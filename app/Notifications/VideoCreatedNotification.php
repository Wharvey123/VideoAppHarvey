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

    /**
     * @var Video
     */
    public Video $video;

    /**
     * Create a new notification instance.
     *
     * @param  Video  $video
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
        // Opcional: log per a debug quan es crea la notificació
        Log::info('VideoCreatedNotification instanciada per vídeo ID: ' . $video->id);
    }

    /**
     * Get the notification’s delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<string>
     */
    public function via($notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Nou vídeo creat: :title', ['title' => $this->video->title]))
            ->greeting(__('Hola :name,', ['name' => $notifiable->name ?? '']))
            ->line(__('S\'ha creat un nou vídeo amb el títol: :title', ['title' => $this->video->title]))
            ->action(__('Veure vídeo'), url("/video/{$this->video->id}"))
            ->line(__('Gràcies per utilitzar la nostra aplicació!'));
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'video_id'   => $this->video->id,
            'title'      => $this->video->title,
            'created_at' => $this->video->created_at->toIso8601String(),
        ]);
    }

    /**
     * Optional: array representation for database channel, if needed later.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'video_id' => $this->video->id,
            'title'    => $this->video->title,
            'url'      => url("/video/{$this->video->id}"),
        ];
    }
}
