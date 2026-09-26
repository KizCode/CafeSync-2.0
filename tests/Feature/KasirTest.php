<?php

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an authenticated cashier can record a sale and reduce product stock', function () {
    $cashier = User::factory()->create();
    $product = Product::factory()->create(['price' => 15000, 'stock' => 5]);

    $response = $this->actingAs($cashier)
        ->post(route('kasir.store'), [
            'customer_name' => 'Dian',
            'payment_method' => 'tunai',
            'paid_amount' => 50000,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('transactions', [
        'user_id' => $cashier->id,
        'subtotal' => 30000,
        'grand_total' => 30000,
        'payment_method' => 'tunai',
        'customer_name' => 'Dian',
    ]);
    $this->assertDatabaseHas('transaction_items', [
        'product_id' => $product->id,
        'quantity' => 2,
        'total_price' => 30000,
    ]);
    expect($product->refresh()->stock)->toBe(3);
});

test('a cashier can submit zero quantities for products that are not purchased', function () {
    $cashier = User::factory()->create();
    $purchasedProduct = Product::factory()->create(['price' => 10000, 'stock' => 5]);
    $otherProduct = Product::factory()->create(['price' => 15000, 'stock' => 5]);

    $this->actingAs($cashier)
        ->post(route('kasir.store'), [
            'payment_method' => 'qris',
            'paid_amount' => 10000,
            'items' => [
                ['product_id' => $purchasedProduct->id, 'quantity' => 1],
                ['product_id' => $otherProduct->id, 'quantity' => 0],
            ],
        ])
        ->assertRedirect();

    expect($purchasedProduct->refresh()->stock)->toBe(4)
        ->and($otherProduct->refresh()->stock)->toBe(5);
});

test('a cashier can update and cancel a transaction', function () {
    $cashier = User::factory()->create();
    $product = Product::factory()->create(['stock' => 4]);
    $transaction = Transaction::factory()->create([
        'user_id' => $cashier->id,
        'grand_total' => 15000,
        'paid_amount' => 15000,
    ]);
    $transaction->items()->create([
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => 7500,
        'total_price' => 15000,
    ]);

    $this->actingAs($cashier)
        ->put(route('kasir.update', $transaction), [
            'customer_name' => 'Dian',
            'payment_method' => 'qris',
            'paid_amount' => 20000,
        ])
        ->assertRedirect(route('kasir.show', $transaction));

    expect($transaction->refresh()->payment_method)->toBe('qris')
        ->and($transaction->change_amount)->toBe('5000.00');

    $this->actingAs($cashier)
        ->delete(route('kasir.destroy', $transaction))
        ->assertRedirect(route('kasir.index'));

    $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
    expect($product->refresh()->stock)->toBe(6);
});

test('a cashier is sent to payment after preparing a cart', function () {
    $cashier = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Americano', 'price' => 18000, 'stock' => 4]);

    $this->actingAs($cashier)
        ->from(route('kasir.index'))
        ->post(route('kasir.prepare'), [
            'customer_name' => 'Dian',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ])
        ->assertRedirect(route('kasir.create'));

    $this->actingAs($cashier)
        ->get(route('kasir.create'))
        ->assertOk()
        ->assertSee('Americano')
        ->assertSee('Konfirmasi pembayaran');
});

test('a cashier cannot view another cashiers transaction', function () {
    $cashier = User::factory()->create();
    $otherSale = Transaction::factory()->create();

    $this->actingAs($cashier)
        ->get(route('kasir.show', $otherSale))
        ->assertNotFound();
});

test('an admin cannot open the cashier pos', function () {
    $admin = User::factory()->admin()->create();
    $sale = Transaction::factory()->create();

    $this->actingAs($admin)
        ->get(route('kasir.show', $sale))
        ->assertForbidden();
});

test('the cashier dashboard only totals the signed-in cashiers sales', function () {
    $cashier = User::factory()->create();
    $other = User::factory()->create();

    Transaction::factory()->create([
        'user_id' => $cashier->id,
        'grand_total' => 25000,
        'status' => 'lunas',
        'created_at' => now(),
    ]);
    Transaction::factory()->create([
        'user_id' => $other->id,
        'grand_total' => 99000,
        'status' => 'lunas',
        'created_at' => now(),
    ]);

    $this->actingAs($cashier)
        ->get(route('kasir.dashboard'))
        ->assertOk()
        ->assertSee('Rp 25.000')
        ->assertDontSee('Rp 99.000');
});
