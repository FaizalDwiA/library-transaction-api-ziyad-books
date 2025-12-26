<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'book_id' => 1,
            'borrowed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
