<?php

use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'email' => 'test@example.com',
        'tin' => '123-456-789-000',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('login'));

    $this->assertGuest();

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'status' => \App\Models\User::STATUS_PENDING,
    ]);
});