<?php

use App\Actions\Eventacle\CreateEvent;
use App\Actions\Eventacle\PredictEvent;
use App\Actions\Eventacle\PublishWinners;
use App\Actions\Eventacle\UpdateEvent;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUser;
use App\Http\Middleware\CheckAdminPrivileges;
use App\Http\Middleware\CheckEventCreator;
use App\Http\Middleware\CheckEventTiming;
use App\Http\Middleware\CheckExistingWinners;
use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\Prediction;
use App\Models\User;
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
        })->middleware([CheckEventCreator::class])->name('.edit');

        Route::post('/{event}/edit', function (Event $event) {
            (new UpdateEvent)->update(request()->all());

            return redirect()->route(Auth::user()->is_admin ? 'admin.events' : 'event.show', $event)->with('success', 'Event edited successfully.');
        })->middleware([CheckEventCreator::class, CheckEventTiming::class.':predictions'])->name('.update');

        Route::delete('/{event}/delete', function (Event $event) {
            DB::transaction(function () use ($event) {
                $event->delete();
            });

            return redirect()->route(Auth::user()->is_admin ? 'admin.events' : 'dashboard')->with('success', 'Event deleted successfully.');
        })->middleware(CheckEventCreator::class)->name('.delete');

        Route::delete('/{event}/delete-user-predictions/{userId}', function (Event $event, int $userId) {
            $predictions = $event->predictions()->where('user_id', $userId)->get();
            DB::transaction(function () use ($predictions) {
                $predictions->each->delete();
            });

            return redirect()->route('event.show', $event)->with('success', 'User\'s predictions deleted successfully.');
        })->middleware(CheckEventTiming::class.':predictions')->name('.delete-user-predictions');

        Route::get('/{event}/winners', function (Event $event) {
            return Inertia::render('Event/Winners', [
                'event' => $event->load('contests', 'predictions'),
            ]);
        })->middleware([CheckEventCreator::class, CheckEventTiming::class.':winners', CheckExistingWinners::class])->name('.winners');

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
    })->middleware(CheckEventTiming::class.':predictions')->name('.prediction');

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
    Route::middleware(CheckAdminPrivileges::class)->group(function () {
        Route::prefix('/admin')->name('admin')->group(function () {
            Route::get('/panel', function () {
                return Inertia::render('Admin/Show', [
                    'stats' => [
                        'users' => ActivityLog::getModelPeriodicStats(User::class),
                        'events' => ActivityLog::getModelPeriodicStats(Event::class),
                        'predictions' => ActivityLog::getModelPeriodicStats(Prediction::class),
                    ],
                ]);
            })->name('.panel');
            Route::get('/user', function () {
                return Inertia::render('User/Manage', [
                    'users' => User::orderBy('name')->paginate(20),
                ]);
            })->name('.users');
            Route::get('/user/create', function () {
                return Inertia::render('User/CreateOrEdit');
            })->name('.user.create');
            Route::post('/user/create', function () {
                (new CreateNewUser)->create(request()->all());

                return redirect()->route('admin.users')->with('success', 'User created successfully.');
            })->name('.user.new');
            Route::get('/user/{user}/edit', function (User $user) {
                return Inertia::render('User/CreateOrEdit', [
                    'user' => $user,
                ]);
            })->name('.user.edit');
            Route::post('/user/{user}/edit', function (User $user) {
                (new UpdateUser)->update($user, request()->all());

                return redirect()->route('admin.users')->with('success', 'User edited successfully.');
            })->name('.user.update');
            Route::delete('/user/{user}/delete', function (User $user) {
                $user->delete();

                return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
            })->name('.user.delete');
            Route::get('/event', function () {
                return Inertia::render('Event/Manage', [
                    'events' => Event::with('creator')->orderByDesc('start_time')->paginate(20),
                ]);
            })->name('.events');
            Route::get('/event/create', function () {
                return Inertia::render('Event/Create', [
                    'users' => User::orderBy('name')->get(),
                ]);
            })->name('.event.create');
            Route::get('/event/{event}/edit', function (Event $event) {
                return Inertia::render('Event/Edit', [
                    'event' => $event->load('contests'),
                    'users' => User::orderBy('name')->get(),
                ]);
            })->name('.event.edit');
            Route::delete('/event/{event}/delete', function (Event $event) {
                $event->delete();

                return redirect()->route('admin.events')->with('success', 'Event deleted successfully.');
            })->name('.event.delete');
        });
    });
});
