<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('an admin can create a product with an image', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();
    $image = UploadedFile::fake()->image('kopi-susu.jpg');

    $this->actingAs($admin)
        ->post(route('admin.products.store'), [
            'name' => 'Kopi Susu',
            'category_id' => $category->id,
            'price' => 15000,
            'stock' => 20,
            'description' => 'Kopi susu gula aren.',
            'image' => $image,
        ])
        ->assertRedirect(route('admin.products.index'));

    $product = Product::query()->where('name', 'Kopi Susu')->firstOrFail();

    expect($product->category_id)->toBe($category->id)
        ->and($product->image)->not->toBeNull();
    Storage::disk('public')->assertExists($product->image);
});

test('a cashier cannot manage products', function () {
    $cashier = User::factory()->create();

    $this->actingAs($cashier)
        ->get(route('admin.products.index'))
        ->assertForbidden();
});
