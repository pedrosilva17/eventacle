<?php

use App\Actions\Eventacle\CreateEvent;
use App\Actions\Eventacle\PredictEvent;
use App\Actions\Eventacle\PublishWinners;
use App\Http\Middleware\CheckEventCreator;
use App\Http\Middleware\CheckGuestName;
use App\Models\Event;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('/event')->name('event')->group(function () {
    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/create', function () {
            return Inertia::render('Event/Create');
        })->name('.create');
        Route::post('/new', function () {
            (new CreateEvent)->create(request()->all());

            return redirect()->route('dashboard')->with('success', 'Event created.');
        })->name('.new');
    });

    Route::get('/{event}', function (Event $event) {
        return Inertia::render('Event/Show', [
            'event' => $event->load('contests', 'creator', 'predictions', 'leaderboard'),
        ]);
    })->name('.show');

    Route::get('/{event}/predict', function (Event $event) {
        return Inertia::render('Event/Predict', [
            'event' => $event->load('contests', 'predictions'),
        ]);
    })->middleware(CheckGuestName::class)->name('.prediction-form');

    Route::post('/{event}/predict', function (Event $event) {
        (new PredictEvent)->predict(request()->all());
        session()->forget('guest_user_name');

        return redirect()->route('event.show', $event)->with('success', 'Prediction saved successfully.');
    })->name('.predict');

    Route::get('/{event}/winners', function (Event $event) {
        return Inertia::render('Event/Winners', [
            'event' => $event->load('contests', 'predictions'),
        ]);
    })->middleware(CheckEventCreator::class)->name('.winners-form');

    Route::post('/{event}/winners', function (Event $event) {
        (new PublishWinners)->publish(request()->all());

        return redirect()->route('event.show', $event)->with('success', 'Winners published successfully.');
    })->name('.publish-winners');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
