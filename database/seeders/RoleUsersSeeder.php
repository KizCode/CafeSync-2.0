<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUsersSeeder extends Seeder
{
    public function run(): void
    {
        foreach (
            [
                ['username' => 'owner', 'name' => 'Owner', 'email' => 'owner@coffee.com', 'password' => 'owner123', 'role' => 'owner'],
                ['username' => 'gudang', 'name' => 'Staff Gudang', 'email' => 'gudang@coffee.com', 'password' => 'gudang123', 'role' => 'gudang'],
            ] as $attributes
        ) {
            User::query()->updateOrCreate(
                ['username' => $attributes['username']],
                [...$attributes, 'is_admin' => false, 'is_active' => true],
            );
        }
    }
}
