<?php

use App\Enums\Language;
use App\Models\{Article, Category, Source, User};

use function Pest\Laravel\{actingAs, get, post};

uses(Tests\TestCase::class);

it('should get Article Filters', function () {
    actingAs(User::factory()->create());

    get(route('articles.filters'))
        ->assertJson([
            'status'     => true,
            'message'    => 'Filters Fetched Successfully.',
            'languages'  => Language::listOptions(),
            'sources'    => Source::listOptions()->toArray(),
            'categories' => Category::listOptions()->toArray(),
        ])->assertSuccessful();
});

it('should get all articles when no filters', function () {
    actingAs(User::factory()->create());

    Article::factory(10)->create();

    $response = get(route('articles.index'))
        ->assertJson([
            'status'  => true,
            'message' => 'Articles Fetched Successfully.',
        ])->assertSuccessful();

    expect($response->json('articles'))
        ->data->toHaveCount(10);
});
