<?php

namespace Database\Factories;

use App\Models\Multimedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MultimediaFactory extends Factory
{
    protected $model = Multimedia::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(), // Afegir parèntesis a sentence()
            'description' => $this->faker->paragraph(), // Afegir parèntesis a paragraph()
            'path' => 'multimedia/' . $this->faker->uuid() . '.jpg', // Afegir parèntesis a uuid()
            'type' => $this->faker->randomElement(['image', 'video']),
            'user_id' => User::factory()
        ];
    }
}
