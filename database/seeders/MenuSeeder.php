<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect(['Coffee', 'Non Coffee', 'Food'])
            ->mapWithKeys(fn(string $name): array => [$name => Category::query()->updateOrCreate(['name' => $name], ['description' => $name . ' CafeSync'])]);

        foreach (
            [
                ['name' => 'Americano', 'category' => 'Coffee', 'price' => 18000],
                ['name' => 'Cappuccino', 'category' => 'Coffee', 'price' => 24000],
                ['name' => 'Cafe Latte', 'category' => 'Coffee', 'price' => 24000],
                ['name' => 'Caramel Macchiato', 'category' => 'Coffee', 'price' => 28000],
                ['name' => 'Matcha Latte', 'category' => 'Non Coffee', 'price' => 26000],
                ['name' => 'Chocolate', 'category' => 'Non Coffee', 'price' => 22000],
                ['name' => 'Croissant', 'category' => 'Food', 'price' => 18000],
                ['name' => 'Sandwich', 'category' => 'Food', 'price' => 28000],
            ] as $menu
        ) {
            Product::query()->updateOrCreate(
                ['name' => $menu['name']],
                [
                    'category_id' => $categories[$menu['category']]->id,
                    'price' => $menu['price'],
                    'stock' => 25,
                    'description' => $menu['name'] . ' CafeSync',
                ],
            );
        }
    }
}
