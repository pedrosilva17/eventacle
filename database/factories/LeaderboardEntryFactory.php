<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeaderboardEntry>
 */
class LeaderboardEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $event = Event::get()->random();
        $isAuthenticated = fake()->boolean();
        $userId = $isAuthenticated ? User::get()->random()->id : null;

        return [
            'user_id' => $userId,
            'user_name' => $userId !== null ? User::where('id', '=', $userId)->first()->name : fake()->name().' (Guest)',
            'event_id' => $event->id,
            'score' => fake()->randomDigit(),
        ];
    }
}
