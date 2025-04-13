<?php

namespace App\Helpers;

use App\Models\Serie;

class SeriesHelper
{
    /**
     * Crea 3 sèries per defecte.
     */
    public static function create_series(): void
    {
        $defaultSeries = [
            // Sèrie 1: The Walking Dead
            [
                'title'          => 'The Walking Dead',
                'description'    => 'Una sèrie post-apocalíptica que segueix un grup de supervivents en un món ple de zombis.',
                // Portada de The Walking Dead:
                'image'          => 'https://cdn.hobbyconsolas.com/sites/navi.axelspringer.es/public/media/image/2018/02/walking-dead-8.jpg?tf=3840x',
                'user_name'      => 'Rick Grimes',
                'user_photo_url' => 'https://hips.hearstapps.com/hmg-prod/images/the-walking-dead-rick-grimes-regreso-teoria-temporada-10-1553704149.jpg?crop=0.582xw:0.873xh;0.166xw,0.127xh&resize=1200:*',
                'published_at'   => now()
            ],
            // Sèrie 2: The 100
            [
                'title'          => 'The 100',
                'description'    => 'Una sèrie on un grup de joves tornen a la Terra per avaluar si és habitable després d’un apocalipsi nuclear.',
                // Portada de The 100:
                'image'          => 'https://www.casaspammer.com/wp-content/uploads/2015/01/qdfc7pzsrjm-market_maxres.jpg',
                'user_name'      => 'Lexa',
                'user_photo_url' => 'https://static.wikia.nocookie.net/thehundred/images/6/67/S3_episode_4_-_Lexa.jpg/',
                'published_at'   => now()
            ],
            // Sèrie 3: The Witcher
            [
                'title'          => 'The Witcher',
                'description'    => 'Una sèrie de fantasia basada en els contes d’un caçador de monstres en un món fosc i perillós.',
                // Portada de The Witcher:
                'image'          => 'https://cdn.mos.cms.futurecdn.net/zV8oTyn3AEfXibuuFLKez8-1200-80.jpg',
                'user_name'      => 'Geralt',
                'user_photo_url' => 'https://static.wikia.nocookie.net/witcher/images/5/51/Netflix_geralt_shirt.jpg/',
                'published_at'   => now()
            ]
        ];

        foreach ($defaultSeries as $serie) {
            // Utilitzem updateOrCreate basant-nos en el camp "title"
            Serie::updateOrCreate(['title' => $serie['title']], $serie);
        }
    }
}
