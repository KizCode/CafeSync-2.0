<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users are redirected to their role dashboard after login', function (string $role, string $route) {
    $user = User::factory()->create([
        'role' => $role,
        'is_admin' => $role === 'admin',
        'email' => $role.'@coffee.test',
        'password' => 'password',
    ]);

    $this->post(route('login.store'), [
        'identifier' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route($route));
})->with([
    'owner' => ['owner', 'owner.index'],
    'admin' => ['admin', 'admin.index'],
    'cashier' => ['kasir', 'kasir.index'],
    'warehouse' => ['gudang', 'gudang.index'],
]);

test('cashiers cannot access admin product management', function () {
    $cashier = User::factory()->create(['role' => 'kasir']);

    $this->actingAs($cashier)
        ->get(route('admin.products.index'))
        ->assertForbidden();
});

test('admins can access product management', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk();
});

test('admins cannot access the cashier pos', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('kasir.index'))
        ->assertForbidden();
});
