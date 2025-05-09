<?php

namespace App\Listeners;

use App\Events\VideoCreated;
use App\Models\User;
use App\Notifications\VideoCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        // Log per a depuració
        Log::info('Evento VideoCreated disparado para vídeo ID: ' . $event->video->id);

        // Obtenir tots els superadmins (camp boolean super_admin = true)
        $admins = User::where('super_admin', true)->get();

        if ($admins->isEmpty()) {
            Log::warning('No se encontraron usuarios con super_admin = true para VideoCreated ID: ' . $event->video->id);
            return;
        }

        // Enviar notificació per mail i broadcast
        Notification::send($admins, new VideoCreatedNotification($event->video));

        Log::info('VideoCreatedNotification enviada a superadmins para vídeo ID: ' . $event->video->id);
    }
}
