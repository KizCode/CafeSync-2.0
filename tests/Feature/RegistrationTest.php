<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the registration page can be rendered', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('Buat akun baru')
        ->assertSee('Data Pribadi');
});

test('a guest is sent back to the first step when password details are missing', function () {
    $this->get(route('register.password'))
        ->assertRedirect(route('register'));
});

test('a guest can complete owner registration and is signed in as a cashier', function () {
    $this->from(route('register'))
        ->post(route('register.details'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '812-3456-7890',
        ])
        ->assertRedirect(route('register.password'));

    $this->from(route('register.password'))
        ->post(route('register.store'), [
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => '1',
        ])
        ->assertRedirect(route('kasir.index'));

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'role' => 'kasir',
        'is_active' => true,
    ]);
});

test('registration is rejected when the email is already taken', function () {
    User::factory()->create(['email' => 'budi@example.com']);

    $this->from(route('register'))
        ->post(route('register.details'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '81234567890',
        ])
        ->assertRedirect(route('register'))
        ->assertInvalid('email');
});

test('registration is rejected when the terms are not accepted', function () {
    $this->withSession([
        'registration' => [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '81234567890',
        ],
    ])->from(route('register.password'))
        ->post(route('register.store'), [
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('register.password'))
        ->assertInvalid('terms');

    $this->assertGuest();
});
