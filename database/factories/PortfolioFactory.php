<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portfolio>
 */
class PortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'            => $this->faker->unique()->word,
            'shortcode'       => $this->faker->unique()->word,
            'type'            => $this->faker->randomElement(['insurance', 're-insurance']),

            //
        ];
    }
}
