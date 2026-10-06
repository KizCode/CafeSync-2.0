<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an admin can view the access matrix', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.access'))
        ->assertOk()
        ->assertSee('Hak akses peran')
        ->assertSee('Kelola User')
        ->assertSee('Admin/Manager');
});

test('non admins cannot view the access matrix', function (string $role) {
    $user = User::factory()->create([
        'role' => $role,
        'is_admin' => false,
    ]);

    $this->actingAs($user)
        ->get(route('admin.access'))
        ->assertForbidden();
})->with(['owner', 'kasir', 'gudang']);

test('non admins cannot open other admin pages', function (string $role, string $route) {
    $user = User::factory()->create([
        'role' => $role,
        'is_admin' => false,
    ]);

    $this->actingAs($user)
        ->get(route($route))
        ->assertForbidden();
})->with([
    'owner dashboard' => ['owner', 'admin.index'],
    'owner users' => ['owner', 'admin.users.index'],
    'owner products' => ['owner', 'admin.products.index'],
    'cashier dashboard' => ['kasir', 'admin.index'],
    'cashier users' => ['kasir', 'admin.users.index'],
    'cashier reports' => ['kasir', 'admin.laporan'],
    'cashier analytics' => ['kasir', 'admin.analitik'],
    'warehouse dashboard' => ['gudang', 'admin.index'],
    'warehouse users' => ['gudang', 'admin.users.index'],
]);

test('an admin can open other role areas', function (string $route) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route($route))
        ->assertOk();
})->with([
    'owner' => ['owner.index'],
    'warehouse' => ['gudang.index'],
    'cashier pos' => ['kasir.index'],
    'cashier orders' => ['kasir.pesanan'],
]);
