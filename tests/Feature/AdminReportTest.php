<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an admin can view the monthly sales report', function () {
    $admin = User::factory()->admin()->create();
    Transaction::factory()->create([
        'invoice_number' => 'INV-ADMIN-001',
        'status' => 'lunas',
        'grand_total' => 42000,
        'created_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.laporan'))
        ->assertOk()
        ->assertSee('Laporan penjualan')
        ->assertSee('INV-ADMIN-001')
        ->assertSee('42.000')
        ->assertSee('Export CSV');
});

test('an admin can download a csv of paid sales', function () {
    $admin = User::factory()->admin()->create();
    Transaction::factory()->create([
        'invoice_number' => 'INV-CSV-009',
        'customer_name' => 'Sari',
        'status' => 'lunas',
        'payment_method' => 'tunai',
        'grand_total' => 15000,
    ]);
    Transaction::factory()->create([
        'invoice_number' => 'INV-SKIP',
        'status' => 'batal',
        'grand_total' => 88000,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.laporan.export'));

    $response->assertOk()
        ->assertHeader('content-disposition', 'attachment; filename=laporan-penjualan-'.now()->format('Y-m-d').'.csv');

    expect($response->streamedContent())
        ->toContain('INV-CSV-009')
        ->toContain('Sari')
        ->not->toContain('INV-SKIP');
});

test('an owner can view sales reports and analytics', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    Transaction::factory()->create([
        'invoice_number' => 'INV-OWNER-010',
        'status' => 'lunas',
        'grand_total' => 31000,
        'created_at' => now(),
    ]);

    $this->actingAs($owner)
        ->get(route('admin.laporan'))
        ->assertOk()
        ->assertSee('Laporan penjualan')
        ->assertSee('INV-OWNER-010')
        ->assertSee('Dashboard')
        ->assertSee('Analitik')
        ->assertDontSee('Hak Akses');

    $this->actingAs($owner)
        ->get(route('admin.analitik'))
        ->assertOk()
        ->assertSee('Analitik bisnis');
});

test('a cashier cannot view sales reports', function () {
    $cashier = User::factory()->create(['role' => 'kasir']);

    $this->actingAs($cashier)
        ->get(route('admin.laporan'))
        ->assertForbidden();
});

test('an admin can view payment analytics', function () {
    $admin = User::factory()->admin()->create();
    Transaction::factory()->create([
        'status' => 'lunas',
        'payment_method' => 'qris',
        'grand_total' => 18000,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.analitik'))
        ->assertOk()
        ->assertSee('Analitik bisnis')
        ->assertSee('Qris');
});
