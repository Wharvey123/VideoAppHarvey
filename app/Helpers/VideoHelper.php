<?php

namespace App\Helpers;

use App\Models\Video;

class VideoHelper
{
    /**
     * Crea o actualitza vídeos per defecte a la base de dades.
     *
     * Es creen 3 vídeos per cada sèrie:
     *  - The Walking Dead (series_id = 1, usuari: Rick Grimes - user_id = 1)
     *  - The 100 (series_id = 2, usuari: Lexa - user_id = 2)
     *  - The Witcher (series_id = 3, usuari: Geralt - user_id = 3)
     *
     * Per cada sèrie, es defineixen els camps "previous" i "next" segons la seqüència.
     *
     * @return void
     */
    public static function createDefaultVideos(): void
    {
        $defaultVideos = [
            // Vídeos per The Walking Dead (series_id = 1, user_id = 1)
            [
                'title' => 'The Walking Dead - Episodi 1',
                'description' => 'Primer episodi de The Walking Dead.',
                'url' => 'https://www.youtube.com/embed/sfAc2U20uyg',
                'published_at' => now(),
                'previous' => null,
                'next' => 2,
                'series_id' => 1,
                'user_id' => 1,
            ],
            [
                'title' => 'The Walking Dead - Episodi 2',
                'description' => 'Segon episodi de The Walking Dead.',
                'url' => 'https://www.youtube.com/embed/1OZ0mu8Ey6A?list=PLT7CPOsjXUsEPTnmREcOBu5T4RyERXD-s',
                'published_at' => now(),
                'previous' => 1,
                'next' => 3,
                'series_id' => 1,
                'user_id' => 1,
            ],
            [
                'title' => 'The Walking Dead - Episodi 3',
                'description' => 'Tercer episodi de The Walking Dead.',
                'url' => 'https://www.youtube.com/embed/uAZ8vYPoMh8?list=PLT7CPOsjXUsEPTnmREcOBu5T4RyERXD-s',
                'published_at' => now(),
                'previous' => 2,
                'next' => null,
                'series_id' => 1,
                'user_id' => 1,
            ],
            // Vídeos per The 100 (series_id = 2, user_id = 2)
            [
                'title' => 'The 100 - Episodi 1',
                'description' => 'Primer episodi de The 100.',
                'url' => 'https://www.youtube.com/embed/aDrsItJ_HU4',
                'published_at' => now(),
                'previous' => null,
                'next' => 2,
                'series_id' => 2,
                'user_id' => 2,
            ],
            [
                'title' => 'The 100 - Episodi 2',
                'description' => 'Segon episodi de The 100.',
                'url' => 'https://www.youtube.com/embed/NepXdwVRVtY',
                'published_at' => now(),
                'previous' => 1,
                'next' => 3,
                'series_id' => 2,
                'user_id' => 2,
            ],
            [
                'title' => 'The 100 - Episodi 3',
                'description' => 'Tercer episodi de The 100.',
                'url' => 'https://www.youtube.com/embed/uwxwHTu802M',
                'published_at' => now(),
                'previous' => 2,
                'next' => null,
                'series_id' => 2,
                'user_id' => 2,
            ],
            // Vídeos per The Witcher (series_id = 3, user_id = 3)
            [
                'title' => 'The Witcher - Episodi 1',
                'description' => 'Primer episodi de The Witcher.',
                'url' => 'https://www.youtube.com/embed/ndl1W4ltcmg',
                'published_at' => now(),
                'previous' => null,
                'next' => 2,
                'series_id' => 3,
                'user_id' => 3,
            ],
            [
                'title' => 'The Witcher - Episodi 2',
                'description' => 'Segon episodi de The Witcher.',
                'url' => 'https://www.youtube.com/embed/2aMVzFlApa0',
                'published_at' => now(),
                'previous' => 1,
                'next' => 3,
                'series_id' => 3,
                'user_id' => 3,
            ],
            [
                'title' => 'The Witcher - Episodi 3',
                'description' => 'Tercer episodi de The Witcher.',
                'url' => 'https://www.youtube.com/embed/SzS8Ao0H6Co',
                'published_at' => now(),
                'previous' => 2,
                'next' => null,
                'series_id' => 3,
                'user_id' => 3,
            ],
        ];

        // Per a cada vídeo per defecte, crear o actualitzar el registre a la base de dades
        foreach ($defaultVideos as $video) {
            Video::updateOrCreate(
                ['title' => $video['title']],
                $video
            );
        }
    }
}
