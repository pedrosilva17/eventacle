<?php

use App\Actions\Eventacle\CreateEvent;
use App\Actions\Eventacle\PredictEvent;
use App\Actions\Eventacle\PublishWinners;
use App\Actions\Eventacle\UpdateEvent;
use App\Http\Middleware\CheckEventCreator;
use App\Http\Middleware\CheckEventTiming;
use App\Http\Middleware\CheckExistingWinners;
use App\Models\Event;
use App\Models\LeaderboardEntry;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->user()) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('Welcome');
})->name('welcome');

Route::prefix('/event')->name('event')->group(function () {
    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/create', function () {
            return Inertia::render('Event/Create');
        })->name('.create');

        Route::post('/create', function () {
            $event = (new CreateEvent)->create(request()->all());

            return redirect()->route('event.show', $event)->with('success', 'Event created successfully.');
        })->name('.new');

        Route::get('/{event}/edit', function (Event $event) {
            return Inertia::render('Event/Edit', [
                'event' => $event->load('contests'),
            ]);
        })->middleware([CheckEventCreator::class])->name('.edit-form');

        Route::post('/{event}/edit', function (Event $event) {
            (new UpdateEvent)->update(request()->all());

            return redirect()->route('event.show', $event)->with('success', 'Event edited successfully.');
        })->middleware([CheckEventCreator::class, CheckEventTiming::class.':predictions'])->name('.edit');

        Route::delete('/{event}/delete', function (Event $event) {
            DB::transaction(function () use ($event) {
                LeaderboardEntry::where('event_id', $event->id)->update(['event_id' => null]);
                $event->delete();
            });

            return redirect()->route('dashboard')->with('success', 'Event deleted successfully.');
        })->middleware(CheckEventCreator::class)->name('.delete');

        Route::get('/{event}/winners', function (Event $event) {
            return Inertia::render('Event/Winners', [
                'event' => $event->load('contests', 'predictions'),
            ]);
        })->middleware([CheckEventCreator::class, CheckEventTiming::class.':winners', CheckExistingWinners::class])->name('.winners-form');

        Route::post('/{event}/winners', function (Event $event) {
            (new PublishWinners)->publish(request()->all());

            return redirect()->route('event.show', $event)->with('success', 'Winners published successfully.');
        })->middleware([CheckEventCreator::class, CheckEventTiming::class.':winners', CheckExistingWinners::class])->name('.publish-winners');
    });

    Route::get('/{event}', function (Event $event) {
        return Inertia::render('Event/Show', [
            'event' => $event->load('contests', 'creator', 'predictions', 'leaderboard'),
            'predictionsByContest' => $event->predictionsByContest(),
        ]);
    })->name('.show');

    Route::get('/{event}/predict', function (Event $event) {
        return Inertia::render('Event/Predict', [
            'event' => $event->load('contests', 'predictions'),
            'userId' => Auth::id(),
        ]);
    })->middleware(CheckEventTiming::class.':predictions')->name('.prediction-form');

    Route::post('/{event}/predict', function (Event $event) {
        (new PredictEvent)->predict(request()->all());

        return redirect()->route('event.show', $event)->with('success', 'Prediction saved successfully.');
    })->middleware(CheckEventTiming::class.':predictions')->name('.predict');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'eventsCreated' => Auth::user()->eventsCreated()->where('has_winners', '=', false)->orderBy('start_time')->get()->load('contests', 'predictions'),
            'eventsPredicted' => Auth::user()->eventsPredicted()->filter(function (Event $event) {
                return ! $event->has_winners;
            })->sortBy('start_time')->values()->load('contests', 'predictions'),
            'userPredictions' => Auth::user()->predictions()->get()->load('contest')->groupBy('event_id'),
        ]);
    })->name('dashboard');
});
