<?php

namespace App\Actions\Eventacle;

use App\Models\Contest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateEvent
{
    /**
     * Update the given event and associated contests.
     */
    public static function update(array $input): void
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
            'distinct' => 'Each option must have a unique name.',
        ])->validate();

        $event = Event::where('id', $input['event']['id'])->first();
        $startTime = Carbon::parse($input['start_time'], $input['user_timezone']);
        $utcTime = $startTime->setTimezone('UTC')->toISOString();

        $event->update([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'start_time' => $utcTime,
            'scoring_type' => $input['scoring_type'],
        ]);
        $event->creator_id = $input['creator_id'];
        $event->save();

        foreach ($input['contests'] as $contestInfo) {
            if (! array_key_exists('id', $contestInfo)) {
                $contest = Contest::make([
                    'name' => $contestInfo['name'],
                    'description' => $contestInfo['description'] ?? null,
                    'options' => implode('|SEP|', str_replace('|SEP|', '', $contestInfo['options'])),
                ]);
                $contest->event_id = $event->id;
                $contest->save();
            } else {
                $contest = Contest::find($contestInfo['id']);

                if ($contest && $contest->event_id === $event->id) {
                    $contest->update([
                        'name' => $contestInfo['name'],
                        'description' => $contestInfo['description'] ?? null,
                        'options' => implode('|SEP|', str_replace('|SEP|', '', $contestInfo['options'])),
                    ]);
                }
            }
        }
        $deletedContestIds = array_diff(
            $event->contests->pluck('id')->toArray(),
            Arr::pluck($input['contests'], 'id')
        );
        foreach ($deletedContestIds as $contestId) {
            Contest::destroy($contestId);
        }
    }
}
