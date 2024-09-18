<?php

namespace App\Actions\Eventacle;

use App\Models\Contest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateEvent
{
    /**
     * Create a new event.
     */
    public function create(array $input): Event
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:70'],
            'description' => ['nullable', 'string', 'max:300'],
            'start_time' => ['required', 'date', 'after:today'],
            'contests' => ['required', 'array'],
            'contests.*.name' => ['required_with:contests', 'string', 'max:120'],
            'contests.*.description' => ['nullable', 'string', 'max:300'],
            'contests.*.options' => ['required_with:contests', 'array'],
            'contests.*.options.*' => ['required_with:contests', 'string', 'max:70'],
        ], [
            'required' => 'This field is required.',
            'required_with' => 'This field is required.',
            'max' => 'Keep this field under :max characters.',
        ])->validate();

        $event = Event::make([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'start_time' => $input['start_time'],
            'scoring_type' => $input['scoring_type'],
        ]);
        $event->creator_id = Auth::id();
        $event->save();

        if (isset($input['contests']) && is_array($input['contests'])) {
            foreach ($input['contests'] as $contest) {
                $newContest = Contest::make([
                    'name' => $contest['name'],
                    'description' => $contest['description'] ?? null,
                    'options' => implode('|', str_replace('|', '', $contest['options'])),
                ]);
                $newContest->event_id = $event->id;
                $newContest->save();
            }
        }

        return $event;
    }
}
