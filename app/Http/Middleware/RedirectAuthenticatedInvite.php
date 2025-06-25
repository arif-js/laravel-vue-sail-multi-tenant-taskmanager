<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticatedInvite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && $request->has('invite_token') && $request->has('team_id')) {
            return redirect()->route('teams.accept', [
                'team' => $request->team_id,
                'token' => $request->invite_token,
            ]);
        }

        return $next($request);
    }
}
