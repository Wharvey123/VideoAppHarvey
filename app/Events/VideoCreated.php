<?php

namespace App\Events;

use App\Models\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class VideoCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public Video $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function broadcastOn(): Channel
    {
        // private or public channel as you choose
        return new Channel('videos');
    }

    public function broadcastAs(): string
    {
        return 'video.created';
    }
}
