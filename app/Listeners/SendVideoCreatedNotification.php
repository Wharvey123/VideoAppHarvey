<?php

namespace App\Listeners;

use App\Events\VideoCreated;
use App\Models\User;
use App\Notifications\VideoCreatedNotification;
use App\Mail\VideoCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
class SendVideoCreatedNotification implements ShouldQueue
{
    /** @param  VideoCreated  $event
     * @return void */
    public function handle(VideoCreated $event): void
    {
        // Obtenir tots els superadmins (camp boolean super_admin = true)
        $admins = User::where('super_admin', true)->distinct('email')->get();
        if ($admins->isEmpty()) {return;}

        // 1) Enviem un email individual a cada superadmin amb Mail::send
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new VideoCreatedMail($event->video, $admin));
        }

        // 2) Enviem la notificació via mail, broadcast i database
        Notification::send($admins, new VideoCreatedNotification($event->video));
    }
}
