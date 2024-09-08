<?php

namespace App\Actions;

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
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'start_time' => ['required', 'date'],
            'contests' => ['nullable', 'array'],
            'contests.*.name' => ['required_with:contests', 'string', 'max:70'],
            'contests.*.description' => ['nullable', 'string'],
            'contests.*.options' => ['required_with:contests', 'array'],
            'contests.*.options.*' => ['required_with:contests', 'string'],
        ], ['required' => 'Required field!', 'required_with' => 'Required field!'])->validate();

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
