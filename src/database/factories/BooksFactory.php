<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'stock' => $this->faker->numberBetween(0, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
