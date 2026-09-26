<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an admin is redirected directly to user management after logging in', function () {
    $user = User::factory()->admin()->create(['email' => 'admin@example.com']);

    $this->withSession(['url.intended' => route('login')])->post(route('login.store'), [
        'identifier' => $user->email,
        'password' => 'password',
    ])
        ->assertRedirect(route('admin.index'));

    $this->assertAuthenticatedAs($user);
});

test('a cashier cannot access the admin dashboard', function () {
    $cashier = User::factory()->create();

    $this->actingAs($cashier)
        ->get(route('admin.index'))
        ->assertForbidden();
});

test('a user can log in with their name', function () {
    $user = User::factory()->create(['name' => 'Dian Permata']);

    $this->post(route('login.store'), [
        'identifier' => $user->name,
        'password' => 'password',
    ])
        ->assertRedirect(route('kasir.index'));

    $this->assertAuthenticatedAs($user);
});

test('a user can log in with their username', function () {
    $user = User::factory()->create(['username' => 'dianpermata']);

    $this->post(route('login.store'), [
        'identifier' => $user->username,
        'password' => 'password',
    ])
        ->assertRedirect(route('kasir.index'));

    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with invalid credentials', function () {
    User::factory()->create(['email' => 'dian@example.com']);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'identifier' => 'dian@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('login'))
        ->assertInvalid('identifier');

    $this->assertGuest();
});

test('an authenticated user can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
