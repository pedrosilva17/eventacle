<?php

namespace App\Actions\Eventacle;

use App\Models\Contest;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;

class EditEvent
{
    /**
     * Edit the given event and associated contests.
     */
    public function edit(array $input): void
    {
        Validator::make($input, [
            'description' => ['nullable', 'string', 'max:300'],
            'start_time' => ['required', 'date', 'after:today'],
            'contests' => ['required', 'array'],
            'contests.*.name' => ['required_with:contests', 'string', 'max:120'],
            'contests.*.description' => ['nullable', 'string', 'max:300'],
        ])->validate();

        $event = Event::where('id', $input['event']['id'])->first();

        $event->update([
            'description' => $input['description'] ?? null,
            'start_time' => $input['start_time'],
        ]);

        foreach ($input['contests'] as $contestInfo) {
            $contest = Contest::find($contestInfo['id']);

            if ($contest && $contest->event_id === $event->id) {
                $contest->update([
                    'name' => $contestInfo['name'],
                    'description' => $contestInfo['description'] ?? null,
                ]);
            }
        }
    }
}
