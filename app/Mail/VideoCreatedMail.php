<?php

namespace App\Mail;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VideoCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Video $video;
    public $user;

    public function __construct(Video $video, $user)
    {
        $this->video = $video;
        $this->user  = $user;
    }

    public function build(): VideoCreatedMail
    {
        return $this
            ->subject('Nou vídeo creat: ' . $this->video->title)
            ->view('emails.video_created')
            ->with([
                'video' => $this->video,
                'user'  => $this->user,
            ]);
    }
}
