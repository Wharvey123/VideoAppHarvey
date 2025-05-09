<?php

namespace App\Listeners;

use App\Events\VideoCreated;
use App\Models\User;
use App\Notifications\VideoCreatedNotification;
use App\Mail\VideoCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendVideoCreatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  VideoCreated  $event
     * @return void
     */
    public function handle(VideoCreated $event): void
    {
        Log::info('Evento VideoCreated disparado para vídeo ID: ' . $event->video->id);

        // Obtenir tots els superadmins (camp boolean super_admin = true)
        $admins = User::where('super_admin', true)->get();

        Log::info('Nombre de superadmins trobats: ' . $admins->count());

        if ($admins->isEmpty()) {
            Log::warning('No se encontraron usuarios con super_admin = true para VideoCreated ID: ' . $event->video->id);
            return;
        }

        // 1) Enviem un email individual a cada super-admin
        foreach ($admins as $admin) {
            Log::info("Enviant correu a {$admin->email} per vídeo ID: {$event->video->id}");
            Mail::to($admin->email)
                ->send(new VideoCreatedMail($event->video, $admin));
            Log::info("Correu enviat a {$admin->email} per vídeo ID: {$event->video->id}");
        }

        // 2) Enviem també la notificació via Broadcast (push)
        Log::info('Enviant notificació broadcast a superadmins per vídeo ID: ' . $event->video->id);
        Notification::send($admins, new VideoCreatedNotification($event->video));
        Log::info('VideoCreatedNotification enviada a superadmins per vídeo ID: ' . $event->video->id);
    }
}
