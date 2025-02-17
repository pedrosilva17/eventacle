<?php

namespace App\Actions\Eventacle;

use App\Models\Contest;
use App\Models\Event;
use App\Models\LeaderboardEntry;
use App\Models\Prediction;
use Illuminate\Support\Facades\Validator;

class PublishWinners
{
    /**
     * Publish an event's winners.
     */
    public function publish(array $input): array
    {
        $contestCount = count($input['event']['contests']);
        Validator::make($input, [
            'results' => ['required', 'array', 'size:'.$contestCount],
            'results.*' => ['required_with:results', 'string'],
        ], [
            'required' => 'This field is required.',
            'size' => 'Every contest must have a winner.',
        ])->validate();
        foreach ($input['event']['contests'] as $contest) {
            $contest['result'] = $input['results'][$contest['id']];
            Contest::where('id', $contest['id'])->update([
                'result' => $contest['result'],
            ]);
        }

        $eventId = $input['event']['id'];
        $eventSlug = $input['event']['slug'];
        $event = Event::find($eventId);
        $event->has_winners = true;
        $event->save();

        return $this->calculateLeaderboard($input['event']['predictions'], $eventId, $eventSlug);
    }

    protected function calculateLeaderboard(array $predictions, int $eventId, string $eventSlug)
    {
        $entries = [];
        $predictions = Prediction::where('event_id', $eventId)->get()->groupBy('user_id');
        if (array_key_exists('', $predictions->toArray())) {
            $guestPredictions = $predictions['']->groupBy('user_name')->toArray();
            unset($predictions['']);
            $predictions = array_replace($predictions->toArray(), $guestPredictions);
        }
        foreach ($predictions as $userId => $userPredictions) {
            $score = 0;
            if (! is_array($userPredictions)) {
                $userPredictions = $userPredictions->toArray();
            }
            foreach ($userPredictions as $prediction) {
                $contest = Contest::find($prediction['contest_id']);
                if ($prediction['prediction_name'] === $contest['result']) {
                    $score += $prediction['points'];
                }
            }
            $newEntry = LeaderboardEntry::make();
            $newEntry->user_id = gettype($userId) === 'integer' ? $userId : null;
            $newEntry->user_name = $userPredictions[0]['user_name'];
            $newEntry->event_id = $eventId;
            $newEntry->event_slug = $eventSlug;
            $newEntry->score = $score;
            $newEntry->save();
            array_push($entries, $newEntry);
        }

        return $entries;
    }
}
