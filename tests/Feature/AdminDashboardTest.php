<?php

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an admin can view sales summaries on the dashboard', function () {
    $admin = User::factory()->admin()->create();
    $product = Product::factory()->create(['name' => 'Kopi Tubruk', 'stock' => 3]);

    $paid = Transaction::factory()->create([
        'status' => 'lunas',
        'payment_method' => 'qris',
        'grand_total' => 25000,
        'created_at' => now(),
    ]);
    TransactionItem::factory()->create([
        'transaction_id' => $paid->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'total_price' => 25000,
    ]);
    Transaction::factory()->create([
        'status' => 'batal',
        'grand_total' => 90000,
        'created_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.index'))
        ->assertOk()
        ->assertSee('Total pendapatan')
        ->assertSee('25.000')
        ->assertSee('Kopi Tubruk')
        ->assertSee('Peringatan stok')
        ->assertSee('Gudang')
        ->assertSee('Hak Akses')
        ->assertSee('Buka POS')
        ->assertDontSee('>Kasir</a>', false)
        ->assertDontSee('>Owner</a>', false);
});

test('an owner sees the same business dashboard without menu management', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $product = Product::factory()->create(['name' => 'Es Kopi Susu', 'stock' => 2]);

    $paid = Transaction::factory()->create([
        'status' => 'lunas',
        'grand_total' => 18000,
        'created_at' => now(),
    ]);
    TransactionItem::factory()->create([
        'transaction_id' => $paid->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'total_price' => 18000,
    ]);

    $this->actingAs($owner)
        ->get(route('owner.index'))
        ->assertOk()
        ->assertSee('Total pendapatan')
        ->assertSee('18.000')
        ->assertSee('Es Kopi Susu')
        ->assertSee('Pantau pendapatan')
        ->assertSee('Overview')
        ->assertSee('Transaksi terbaru')
        ->assertSee($paid->invoice_number)
        ->assertSee('Laporan')
        ->assertSee('Analitik')
        ->assertDontSee('Kelola menu')
        ->assertDontSee('Hak Akses')
        ->assertDontSee('Buka POS');
});

test('a cashier cannot open the admin or owner dashboard', function () {
    $cashier = User::factory()->create(['role' => 'kasir']);

    $this->actingAs($cashier)
        ->get(route('admin.index'))
        ->assertForbidden();

    $this->actingAs($cashier)
        ->get(route('owner.index'))
        ->assertForbidden();
});

test('guests are redirected away from the admin dashboard', function () {
    $this->get(route('admin.index'))
        ->assertRedirect(route('login'));
});
