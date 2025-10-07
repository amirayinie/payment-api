<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

use function App\utilities\generateRandomValidCardNumber;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CreditCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'card_number' => generateRandomValidCardNumber(),
            'balance_toman' => fake()->numberBetween(),
            'status' => 'active',
        ];
    }

}
