<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGuestName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        if (! auth()->check() && ! $request->session()->has('guest_user_name')) {
            if ($request->has('guest_user_name')) {
                $request->session()->put('guest_user_name', $request->get('guest_user_name'));

                return $next($request);
            }

            return redirect()->route('event.show', $request->route('event'))->with('error', 'Not logged in? Enter your name in this page to make predictions.');
        }

        return $next($request);
    }
}
