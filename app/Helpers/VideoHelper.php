<?php

namespace App\Helpers;

use App\Models\Video;

class VideoHelper
{
    /** Crea o actualitza vídeos per defecte a la base de dades.
     * @return void */
    public static function createDefaultVideos(): void
    {
        // Definim els vídeos per defecte
        $defaultVideos = [
            [
                'title' => 'Introducció a Laravel',
                'description' => 'Un vídeo introductori per aprendre els conceptes bàsics de Laravel.',
                'url' => 'https://www.youtube.com/embed/PGQxIILBb7M',
                'published_at' => now(),
                'previous' => null,
                'next' => 2,
                'series_id' => 1,
                'user_id' => 3,
            ],
            [
                'title' => 'Controladors a Laravel',
                'description' => 'Aprèn com funcionen els controladors a Laravel i com gestionar les rutes.',
                'url' => 'https://www.youtube.com/embed/0YxgCH2R2bE',
                'published_at' => now(),
                'previous' => 1,
                'next' => 3,
                'series_id' => 1,
                'user_id' => 3,
            ],
            [
                'title' => 'Models a Laravel',
                'description' => 'Exploració dels models a Laravel i com interactuar amb la base de dades.',
                'url' => 'https://www.youtube.com/embed/f-pWNf0Ht1Y',
                'published_at' => now(),
                'previous' => 2,
                'next' => null,
                'series_id' => 1,
                'user_id' => 3,
            ],
        ];

        // Per a cada vídeo per defecte, crear o actualitzar el registre a la base de dades
        foreach ($defaultVideos as $video) {
            Video::updateOrCreate(
                ['title' => $video['title']], // Condició per determinar si el vídeo ja existeix
                $video // Dades del vídeo
            );
        }
    }
}
