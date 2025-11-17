<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'category_id' => rand(1, 5),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(200, 2000),
            'active' => true,
        ];
    }
}
