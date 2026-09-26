<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->randomFloat(2, 10000, 100000);

        return [
            'user_id' => User::factory(),
            'invoice_number' => 'INV-'.fake()->unique()->numerify('##########'),
            'subtotal' => $total,
            'grand_total' => $total,
            'payment_method' => 'tunai',
            'paid_amount' => $total,
            'change_amount' => 0,
            'status' => 'lunas',
            'customer_name' => fake()->name(),
        ];
    }
}
