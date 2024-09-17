<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEventCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $event = $request->route('event');
        if (auth()->check() && auth()->user()->id === $event->creator_id) {
            return $next($request);
        }

        return redirect()->route('event.show', $request->route('event'))->with('error', 'You are not the creator of this event!');
    }
}
