<?php

namespace Database\Factories;

use App\Models\Contest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prediction>
 */
class PredictionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = Event::get()->random();
        $contest = Contest::where('event_id', $event->id)->get()->random();
        $options = explode('|', $contest->options);
        $isAuthenticated = fake()->boolean();
        $userId = $isAuthenticated ? User::get()->random()->id : null;

        return [
            'user_id' => $userId,
            'user_name' => $userId !== null ? User::where('id', '=', $userId)->first()->name : fake()->name().' (Guest)',
            'contest_id' => $contest->id,
            'event_id' => $event->id,
            'prediction_name' => $options[fake()->numberBetween(0, count($options) - 1)],
            'points' => fake()->randomNumber(1),
        ];
    }
}
