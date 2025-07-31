<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Event;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;

    public function definition()
    {
        return [
            'organization_id' => 4,
            'title'           => str::title($this->faker->randomElement(['run-provision', 'sync', 'new-user', 'on-boarding', 'COA'])),
            'color'            => $this->faker->randomElement(['primary', 'info', 'success', 'warning', 'danger']),
            'type'            => $this->faker->randomElement(['run-provision', 'sync', 'new-user', 'on-boarding', 'COA']),
            'starts_at'       => $this->faker->dateTimeBetween(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()),
            'ends_at'         => null,
            'description'     => $this->faker->paragraph,
        ];
    }
}
