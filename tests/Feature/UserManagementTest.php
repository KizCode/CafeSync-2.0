<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('an admin can create a user', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.users.create'))
        ->assertOk()
        ->assertSee('Tambah User');

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Dewi Anggraini',
        'username' => 'dewianggraini',
        'email' => 'dewi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('status', 'User berhasil ditambahkan.');

    $user = User::query()->where('email', 'dewi@example.com')->firstOrFail();

    expect($user->name)->toBe('Dewi Anggraini')
        ->and($user->username)->toBe('dewianggraini')
        ->and($user->is_admin)->toBeFalse()
        ->and(Hash::check('password123', $user->password))->toBeTrue();
});

test('a non-admin cannot create a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'Dewi Anggraini',
            'username' => 'dewianggraini',
            'email' => 'dewi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->assertForbidden();

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('an admin must provide valid user details', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.users.create'))
        ->post(route('admin.users.store'), [])
        ->assertRedirect(route('admin.users.create'))
        ->assertInvalid(['name', 'username', 'email', 'password']);
});

test('an admin can view and update a user', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['name' => 'Dewi Lama']);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee('Dewi Lama');

    $this->actingAs($admin)
        ->patch(route('admin.users.update', $user), [
            'name' => 'Dewi Baru',
            'username' => $user->username,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertRedirect(route('admin.users.index'))
        ->assertValid();

    expect($user->refresh()->name)->toBe('Dewi Baru');
});

test('an admin can delete another user but not themselves', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseMissing('users', ['id' => $user->id]);

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertForbidden();
});
