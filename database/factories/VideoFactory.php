<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'url' => $this->faker->word,
            'published_at' => Carbon::now(),
            'previous' => null,
            'next' => null,
            'series_id' => 1,
        ];
    }
}
