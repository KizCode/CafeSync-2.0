<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guests are redirected away from the profile page', function () {
    $this->get(route('profile.edit'))
        ->assertRedirect(route('login'));
});

test('an authenticated user can view their own profile from the account menu', function () {
    $user = User::factory()->create([
        'name' => 'Sari Kasir',
        'username' => 'sarikasir',
        'email' => 'sari.kasir@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Sari Kasir')
        ->assertSee('sarikasir')
        ->assertSee('sari.kasir@example.com')
        ->assertDontSee('name="role"', false);
});

test('the profile link is not in the sidebar', function () {
    $user = User::factory()->create();

    $html = $this->actingAs($user)
        ->get(route('kasir.dashboard'))
        ->assertOk()
        ->assertSee('>Profil</a>', false)
        ->getContent();

    expect(preg_match('/<aside[^>]*id="app-sidebar"[\s\S]*?<\/aside>/', $html, $sidebar))->toBe(1)
        ->and($sidebar[0])->not->toContain(route('profile.edit'));

    expect(preg_match('/<div class="mz-dropdown-panel">([\s\S]*?)<\/div>/', $html, $menu))->toBe(1)
        ->and($menu[1])->toContain('Profil')
        ->and($menu[1])->not->toContain('Dashboard');
});

test('a user can update their profile without changing role', function () {
    $user = User::factory()->create([
        'name' => 'Nama Lama',
        'role' => 'kasir',
        'is_admin' => false,
    ]);

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => 'Nama Baru',
            'username' => $user->username,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'admin',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status', 'Profil berhasil diperbarui.');

    $user->refresh();

    expect($user->name)->toBe('Nama Baru')
        ->and($user->role)->toBe('kasir')
        ->and($user->is_admin)->toBeFalse();
});

test('a user cannot take another account email', function () {
    $other = User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('profile.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $other->email,
            'password' => '',
            'password_confirmation' => '',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertInvalid(['email']);
});

test('a user can change their password from the profile page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('profile.update'), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => 'newpass99',
            'password_confirmation' => 'newpass99',
        ])
        ->assertRedirect(route('profile.edit'));

    expect(Hash::check('newpass99', $user->fresh()->password))->toBeTrue();
});

test('an owner can open the profile page', function () {
    $owner = User::factory()->create(['role' => 'owner', 'name' => 'Pemilik Toko']);

    $this->actingAs($owner)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Pemilik Toko')
        ->assertSee('Profil');
});
