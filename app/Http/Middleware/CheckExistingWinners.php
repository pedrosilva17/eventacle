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
        $areWinnersPublished = $request->route('event')->has_winners;

        return $areWinnersPublished ? redirect()->route('event.show', $request->route('event'))->with('error', 'The winners were already published!') : $next($request);
    }
}
