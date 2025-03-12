<?php

namespace App\Actions\Eventacle;

use App\Models\Contest;
use App\Models\Event;
use Carbon\Carbon;
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
            'start_time' => ['required', 'date', 'after:'.now($input['user_timezone'])->toDateTimeString()],
            'contests' => ['required', 'array'],
            'contests.*.name' => ['required_with:contests', 'string', 'max:120', 'distinct:ignore_case'],
            'contests.*.description' => ['nullable', 'string', 'max:300'],
            'contests.*.options' => ['required_with:contests', 'array'],
            'contests.*.options.*' => ['required_with:contests', 'string', 'max:70', 'distinct:ignore_case'],
        ], [
            'required' => 'This field is required.',
            'required_with' => 'This field is required.',
            'after' => 'Choose a date and time in the future.',
            'max' => 'Keep this field under :max characters.',
        ])->validate();

        $startTime = Carbon::parse($input['start_time'], $input['user_timezone']);
        $utcTime = $startTime->setTimezone('UTC')->toISOString();

        $event = Event::make([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'start_time' => $utcTime,
            'scoring_type' => $input['scoring_type'],
        ]);
        $event->has_winners = false;
        $event->creator_id = $input['creator_id'];
        $event->save();

        if (isset($input['contests']) && is_array($input['contests'])) {
            foreach ($input['contests'] as $contest) {
                $newContest = Contest::make([
                    'name' => $contest['name'],
                    'description' => $contest['description'] ?? null,
                    'options' => implode('|SEP|', str_replace('|SEP|', '', $contest['options'])),
                ]);
                $newContest->event_id = $event->id;
                $newContest->save();
            }
        }

        return $event;
    }
}
