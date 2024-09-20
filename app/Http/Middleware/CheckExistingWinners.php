<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExistingWinners
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $areWinnersDeclared = $request->route('event')->leaderboard()->get()->isNotEmpty();

        return $areWinnersDeclared ? redirect()->route('event.show', $request->route('event'))->with('error', 'The winners were already declared!') : $next($request);
    }
}
