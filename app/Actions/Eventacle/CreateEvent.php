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
            'start_time' => ['required', 'date'],
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

        $event = Event::create([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'creator_id' => Auth::id(),
            'start_time' => $input['start_time'],
        ]);

        if (isset($input['contests']) && is_array($input['contests'])) {
            foreach ($input['contests'] as $contest) {
                Contest::create([
                    'name' => $contest['name'],
                    'description' => $contest['description'] ?? null,
                    'options' => implode('|', $contest['options']),
                    'event_id' => $event->id,
                ]);
            }
        }

        return $event;
    }
}
