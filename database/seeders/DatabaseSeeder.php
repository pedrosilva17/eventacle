<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Event;
use App\Models\LeaderboardEntry;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $users = User::factory(10)->create();

        $events = Event::factory(50)->make()->each(function ($event) use ($users) {
            $event->creator_id = $users->random()->id;
            $event->save();
        });
        $eventsCount = $events->count();

        Contest::factory(300)->make()->each(function ($contest, $index) use ($events, $eventsCount) {
            $contest->event_id = $events[$index % $eventsCount]->id;
            if ($events[$index % $eventsCount]->start_time < Carbon::now()) {
                $options = explode('|SEP|', $contest->options);
                $contest->result = $options[fake()->numberBetween(0, count($options) - 1)];
            }
            $contest->save();
        });

        Event::all()->each(function ($event) {
            $possiblePoints = [];
            $isAuthenticated = fake()->boolean();
            $userId = $isAuthenticated ? User::get()->random()->id : null;
            $fakeName = fake()->name().' (Guest)';
            Contest::where('event_id', '=', $event->id)->orderBy('event_id')->each(function ($contest) use ($event, $possiblePoints, $userId, $fakeName) {
                $options = explode('|SEP|', $contest->options);
                if (empty($possiblePoints)) {
                    $possiblePoints = range(1, count($options));
                    shuffle($possiblePoints);
                }
                Prediction::factory(1)->create([
                    'user_id' => $userId,
                    'user_name' => $userId !== null ? User::find($userId)->name : $fakeName,
                    'event_id' => $event->id,
                    'contest_id' => $contest->id,
                    'contest_name' => $contest->name,
                    'prediction_name' => $options[fake()->numberBetween(0, count($options) - 1)],
                    'points' => $event->scoring_type === 'confidence points' ? array_pop($possiblePoints) : 1,
                ]);
            });
        });
        LeaderboardEntry::factory(200)->make()->each(function ($entry) {
            $entry->event_id = Event::where('start_time', '<', Carbon::now())->get()->random()->id;
            $entry->event_name = Event::find($entry->event_id)->name;
            $entry->save();
        });
    }
}
