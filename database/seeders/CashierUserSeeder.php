<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CashierUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashier = User::query()->firstOrNew([
            'username' => 'kasir',
        ]);

        if (! $cashier->exists) {
            $cashier->fill([
                'name' => 'Kasir',
                'email' => 'kasir@cafesync.test',
                'password' => 'kasir123',
            ]);
        }

        $cashier->forceFill([
            'is_admin' => false,
            'role' => 'kasir',
            'is_active' => true,
            'password' => 'kasir123',
        ])->save();
    }
}
