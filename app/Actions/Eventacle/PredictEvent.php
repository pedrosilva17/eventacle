<?php

namespace App\Actions\Eventacle;

use App\Models\Event;
use App\Models\Prediction;

class PredictEvent
{
    /**
     * Make predictions on all contests of an event.
     */
    public function predict(array $input): array
    {
        $user_id = auth()->check() ? auth()->user()->id : null;
        $user_name = auth()->check() ? auth()->user()->name : substr(request()->session()->get('guest_user_name'), 0, 255);
        $predictions = [];
        foreach (($input['predictions']) as $contest_id => $prediction) {
            $user_id === null ? $existing_prediction = Prediction::where('user_name', $user_name)->where('contest_id', $contest_id) : $existing_prediction = Prediction::where('user_id', $user_id)->where('contest_id', $contest_id);
            if ($existing_prediction->count() > 0) {
                $existing_prediction->update([
                    'prediction_name' => $prediction,
                    'points' => 1,
                ]);
            } else {
                $newPrediction = Prediction::make([
                    'prediction_name' => $prediction,
                    'points' => 1,
                ]);
                $newPrediction->user_id = $user_id;
                $newPrediction->user_name = $user_name;
                $newPrediction->contest_id = $contest_id;
                $newPrediction->event_id = $input['event']['id'];
                $newPrediction->save();
                array_push($predictions, $newPrediction);
            }
        }

        return $predictions;
    }
}
