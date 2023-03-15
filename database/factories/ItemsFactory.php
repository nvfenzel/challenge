<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Items>
 */
class ItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->unique()->word(1),
            'pt_defense' => rand(0,100),
            'pt_attack' => rand(0,100),
            'type' => fake()->randomElement(['bota', 'arma', 'armadura']),
        ];
    }
}
