<?php

namespace Database\Factories;

use App\Models\Serie;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeriesFactory extends Factory
{
    protected $model = Serie::class;

    public function definition(): array
    {
        return [
            'title'          => $this->faker->sentence,
            'description'    => $this->faker->paragraph,
            'image'          => $this->faker->imageUrl(150, 150),
            'user_name'      => $this->faker->name,
            'user_photo_url' => $this->faker->imageUrl(50, 50),
            'published_at'   => now(),
        ];
    }
}
