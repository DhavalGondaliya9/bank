<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrderPayments;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderPayments>
 */
class OrderPaymentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => $this->faker->randomNumber(),
            'transaction_reference' => $this->faker->randomNumber(),
            'amount' => $this->faker->phoneNumber(),
        ];
    }
}
