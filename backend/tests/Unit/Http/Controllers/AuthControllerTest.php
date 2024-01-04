<?php

use App\Models\User;

use function Pest\Laravel\post;

uses(Tests\TestCase::class);

it('should validate required login data', function () {
    $data = [
        'email'    => '',
        'password' => '',
    ];

    post(route('login', $data))
        ->assertJsonValidationErrors([
            'email'    => 'required',
            'password' => 'required',
        ])->assertUnauthorized();
});

it('should validate email when login', function () {
    $data = [
        'email'    => fake()->name(),
        'password' => fake()->password(),
    ];

    post(route('login', $data))
        ->assertJsonValidationErrors([
            'email'    => 'The email field must be a valid email address.',
        ])->assertUnauthorized();
});

it('should validate credentials when login', function () {
    $email = fake()->email();
    User::factory()->create([
        'email' => $email
    ]);

    User::factory()->create();
    $data = [
        'email'    => $email,
        'password' => fake()->password(),
    ];

    post(route('login', $data))
        ->assertJson([
            'status'  => false,
            'message' => 'Email & Password does not match with our record.',
        ])->assertUnauthorized();
});

it('should login', function () {
    $email    = fake()->email();
    $password = fake()->password();
    $user = User::factory()->create([
        'email'    => $email,
        'password' => Hash::make($password),
    ]);

    $data = [
        'email'    => $email,
        'password' => $password,
    ];

    post(route('login', $data))
        ->assertSuccessful();

    expect(auth()->user()->id)->toBe($user->id);
});



it('should validate required data when register', function () {
    $data = [
        'name'              => '',
        'email'             => '',
        'password'          => '',
        'confirmedPassword' => '',
    ];

    post(route('register', $data))
        ->assertJsonValidationErrors([
            'name'              => 'required',
            'email'             => 'required',
            'password'          => 'required',
            'confirmedPassword' => 'required',
        ])->assertUnauthorized();
});

it('should validate if confirmed password is same as password when register', function () {
    $data = [
        'name'              => fake()->name(),
        'email'             => fake()->email(),
        'password'          => fake()->password(),
        'confirmedPassword' => 'test',
    ];

    post(route('register', $data))
        ->assertJsonValidationErrors([
            'confirmedPassword' => 'match password',
        ])->assertUnauthorized();
});

it('should validate duplicated email when register', function () {
    $email    = fake()->email();
    $password = fake()->password();

    User::factory()->create(['email' => $email]);
    $data = [
        'name'              => fake()->name(),
        'email'             => $email,
        'password'          => $password,
        'confirmedPassword' => $password,
    ];

    post(route('register', $data))
        ->assertJsonValidationErrors([
            'email' => 'The email has already been taken.',
        ])->assertUnauthorized();
});

it('should register user with valid data', function () {
    $password = fake()->password();
    $name     = fake()->name();
    $email    = fake()->email();
    $data     = [
        'name'              => $name,
        'email'             => $email,
        'password'          => $password,
        'confirmedPassword' => $password,
    ];

    post(route('register', $data))
        ->assertSuccessful();

    expect(User::get())->toHaveCount(1)
        ->first()->name->toBe($name)
        ->first()->email->toBe($email);
});
