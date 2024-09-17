<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Event;
use App\Models\LeaderboardEntry;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        Contest::factory(200)->make()->each(function ($contest, $index) use ($events, $eventsCount) {
            $contest->event_id = $events[$index % $eventsCount]->id;
            $contest->save();
        });

        Prediction::factory(200)->create();
        LeaderboardEntry::factory(200)->create();
    }
}
