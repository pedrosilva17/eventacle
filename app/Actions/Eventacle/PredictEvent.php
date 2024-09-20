<?php

namespace App\Actions\Eventacle;

use App\Models\Prediction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PredictEvent
{
    /**
     * Make predictions on all contests of an event.
     */
    public function predict(array $input): array
    {
        Validator::make($input, [
            'points' => ['nullable', 'array'],
            'points.*' => ['integer', 'distinct', 'between:1,'.count($input['points'])],
        ], [
            'points.*.distinct' => 'Each contest must have a unique confidence point.',
            'points.*.between' => 'Confidence points must be between :min and :max.',
        ])->validate();
        $userId = auth()->check() ? auth()->user()->id : null;
        $userName = auth()->check() ? auth()->user()->name : substr($input['guest_user_name'], 0, 255);
        $existingNames = Prediction::where('event_id', $input['event']['id'])->pluck('user_name');
        if (! $userId && $existingNames->contains($userName)) {
            throw ValidationException::withMessages([
                'duplicate_name' => 'A user has already made a prediction under that name. Please choose another one.',
            ]);
        }
        $predictions = [];
        foreach (($input['predictions']) as $contestId => $prediction) {
            [$key, $value] = $userId !== null ? ['user_id', $userId] : ['user_name', $userName];
            $existingPrediction = Prediction::where($key, $value)->where('contest_id', $contestId);
            if ($existingPrediction->count() > 0) {
                $existingPrediction->update([
                    'prediction_name' => $prediction,
                    'points' => array_key_exists($contestId, $input['points']) ? $input['points'][$contestId] : 1,
                ]);
            } else {
                $newPrediction = Prediction::make([
                    'prediction_name' => $prediction,
                    'points' => array_key_exists($contestId, $input['points']) ? $input['points'][$contestId] : 1,
                ]);
                $newPrediction->user_id = $userId;
                $newPrediction->user_name = $userName;
                $newPrediction->contest_id = $contestId;
                $newPrediction->event_id = $input['event']['id'];
                $newPrediction->save();
                array_push($predictions, $newPrediction);
            }
        }

        return $predictions;
    }
}
