<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckEventTiming
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $type)
    {
        $event = $request->route('event');
        $startTime = Carbon::parse($event->start_time);

        switch ($type) {
            case 'predictions':
                if (Carbon::now()->isAfter($startTime)) {
                    return redirect()->route('event.show', $event)->with('error', 'You can\'t do that, the event has already started!');
                }
                break;
            case 'winners':
                if (Carbon::now()->isBefore($startTime)) {
                    return redirect()->route('event.show', $event)->with('error', 'You can\'t do that, the event hasn\'t started yet!');
                }
                break;
            default:
                break;
        }

        return $next($request);
    }
}
