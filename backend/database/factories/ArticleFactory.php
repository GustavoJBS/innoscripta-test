<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Models\{Category, Source};
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'        => fake()->title(),
            'url'          => fake()->url(),
            'image'        => fake()->imageUrl(),
            'description'  => fake()->sentence(),
            'language'     => fake()->randomElement(Language::cases()),
            'published_at' => now(),
            'category_id'  => Category::factory(),
            'source_id'    => Source::factory(),
        ];
    }
}
