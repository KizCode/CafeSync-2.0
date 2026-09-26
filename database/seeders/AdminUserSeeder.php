<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrNew([
            'username' => 'admin',
        ]);

        if (! $admin->exists) {
            $admin->fill([
                'name' => 'Admin',
                'email' => 'admin@cafesync.test',
                'password' => 'admin123',
            ]);
        }

        $admin->forceFill([
            'is_admin' => true,
            'role' => 'admin',
            'is_active' => true,
            'password' => 'admin123',
        ])->save();
    }
}
