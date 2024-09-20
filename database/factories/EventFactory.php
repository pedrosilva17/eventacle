<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            //'creator_id' => User::factory(),
            'start_time' => Carbon::now()->addDays(rand(-365, 365))->addHours(rand(1, 23))->addMinutes(rand(1, 59))->format('Y-m-d\TH:i\Z'),
            'scoring_type' => fake()->boolean() ? 'single points' : 'confidence points',
        ];
    }
}
