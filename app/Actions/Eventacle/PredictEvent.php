<?php

namespace App\Actions\Eventacle;

use App\Models\Event;
use App\Models\Prediction;

class PredictEvent
{
    /**
     * Make predictions on all contests of an event.
     *
     * @return array<string, mixed>
     */
    public function predict(array $input): array
    {
        $user_id = auth()->check() ? auth()->user()->id : null;
        $user_name = auth()->check() ? auth()->user()->name : substr(request()->session()->get('guest_user_name'), 0, 255);
        $predictions = [];
        foreach (($input['predictions']) as $contest_id => $prediction) {
            array_push($predictions, Prediction::create([
                'user_id' => $user_id,
                'user_name' => $user_name,
                'contest_id' => $contest_id,
                'event_id' => $input['event']['id'],
                'prediction_name' => $prediction,
                'points' => 1,
            ]));
        }

        return $predictions;
    }
}
