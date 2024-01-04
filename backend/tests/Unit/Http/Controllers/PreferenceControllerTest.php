<?php

use App\Enums\Language;
use App\Models\{Category, Source, User};

use function Pest\Laravel\{actingAs, get,  put};

uses(Tests\TestCase::class);

it('should get user preferences', function () {
    $user = User::factory()->create();

    actingAs($user);

    get(route('preference.index'))
        ->assertSuccessful()
        ->assertJson([
            'status'     => true,
            'message'    => 'User Preferences Fetched Successfully',
            'preference' => $user->preference->toArray(),
        ]);
});

it('should validate user preferences save', function () {
    $user = User::factory()->create();

    actingAs($user);

    put(route('preference.save'), [
        'languages'  => '',
        'sources'    => '',
        'categories' => '',
    ])->assertJsonValidationErrors([
        'languages'  => 'The languages field must be an array.',
        'sources'    => 'The sources field must be an array.',
        'categories' => 'The categories field must be an array.',
    ])->assertMethodNotAllowed();

    put(route('preference.save', [
        'languages'  => [55],
        'sources'    => ['fwadwawd'],
        'categories' => ['dwaadwadw'],
    ]))->assertJsonValidationErrors([
        'sources.0'    => 'The sources.0 field must be a number.',
        'categories.0' => 'The categories.0 field must be a number.',
    ])->assertMethodNotAllowed();

    put(route('preference.save', [
        'languages'  => ['es'],
        'sources'    => [5],
        'categories' => [6],
    ]))->assertJsonValidationErrors([
        'sources.0'    => 'The selected sources.0 is invalid.',
        'categories.0' => 'The selected categories.0 is invalid.',
    ])->assertMethodNotAllowed();
});

it('should save user preferences', function () {
    $this->seed([]);
    $user     = User::factory()->create();
    $category = Category::factory()->create();
    $source   = Source::factory()->create();

    actingAs($user);

    put(route('preference.save', [
        'languages'  => [Language::EN->value],
        'sources'    => [$source->id],
        'categories' => [$category->id],
    ]))->assertJson([
        'status'  => true,
        'message' => 'User Preferences saved Successfully',
        'user'    => auth()->user()->toArray(),
    ])->assertSuccessful();
});
