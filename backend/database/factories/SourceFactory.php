<?php

namespace Database\Factories;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->name(),
            'url'         => fake()->url(),
            'country'     => fake()->country(),
            'language'    => fake()->randomElement(Language::cases()),
            'description' => fake()->sentence(),
        ];
    }
}
