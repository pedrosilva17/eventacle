<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
        Jetstream::inertia()->whenRendering(
            'Profile/Show', function (Request $request, array $data) {
                return array_merge($data, [
                    'eventsCreated' => Auth::user()->eventsCreated()->orderByDesc('start_time')->get()->load('contests', 'predictions'),
                    'eventsPredicted' => Auth::user()->eventsPredicted()->load('contests'),
                    'leaderboardEntries' => Auth::user()->leaderboardEntries()->get(),
                    'totalNumEventsPredicted' => Auth::user()->totalNumEventsPredicted(),
                    'podiums' => Auth::user()->podiums(),
                    'podiumCount' => Auth::user()->podiumCount(),
                    'winRate' => Auth::user()->winRate(),
                    'predictionsByContest' => Auth::user()->predictionsByContest(),
                ]);
            }
        );
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
