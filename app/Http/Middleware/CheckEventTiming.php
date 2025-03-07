<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckEventTiming
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $type, $tz = null)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }
        $event = $request->route('event');
        $startTime = Carbon::parse($event->start_time);

        switch ($type) {
            case 'predictions':
                if (Carbon::now($tz)->isAfter($startTime)) {
                    return redirect()->route('event.show', $event)->with('error', 'You can\'t do that, the event has already started!');
                }
                break;
            case 'winners':
                if (Carbon::now($tz)->isBefore($startTime)) {
                    return redirect()->route('event.show', $event)->with('error', 'You can\'t do that, the event hasn\'t started yet!');
                }
                break;
            default:
                break;
        }

        return $next($request);
    }
}
