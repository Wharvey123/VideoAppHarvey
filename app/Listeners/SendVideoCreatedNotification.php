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
    /** Handle the event. */
    public function handle(VideoCreated $event): void
    {
        Log::info('Evento VideoCreated disparado para vídeo: ' . $event->video->id);
        // Obtenir tots els superadmins (camp boolean super_admin = true)
        $admins = User::where('super_admin', true)->get();

        // Enviar notificació per mail i broadcast
        Notification::send($admins, new VideoCreatedNotification($event->video));
    }
}
