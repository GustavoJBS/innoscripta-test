<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'business',
            'entertainment',
            'general',
            'health',
            'science',
            'sports',
            'technology',
        ])->each(fn (string $category) => $this->saveCategory($category));
    }

    private function saveCategory(string $name): void
    {
        Category::query()->firstOrCreate([
            'name' => $name,
        ]);
    }
}
