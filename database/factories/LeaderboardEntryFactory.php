<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
        //$hasEnded = fake()->boolean();
        //$event = Event::where('start_time', $hasEnded ? '<' : '>', Carbon::now())->get()->random();
        //var_dump($event->start_time);
        $isAuthenticated = fake()->boolean();
        $userId = $isAuthenticated ? User::get()->random()->id : null;

        return [
            'user_id' => $userId,
            'user_name' => $userId !== null ? User::where('id', '=', $userId)->first()->name : fake()->name().' (Guest)',
            //'event_id' => $event->id,
            //'event_name' => $event->name,
            'score' => fake()->randomDigit(),
        ];
    }
}
