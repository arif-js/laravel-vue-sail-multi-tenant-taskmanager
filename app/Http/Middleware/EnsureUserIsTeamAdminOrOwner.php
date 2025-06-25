<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsTeamAdminOrOwner
{
    public function handle(Request $request, Closure $next)
    {
        $team = $request->route('team');

        if (!$team) {
            abort(404, 'Team not found.');
        }

        $user = $request->user();

        $role = $team->users()
            ->where('user_id', $user->id)
            ->first()
            ->pivot
            ->role ?? null;

        if (!in_array($role, ['Owner', 'Admin'])) {
            abort(403, 'Only Owner or Admin can perform this action.');
        }

        return $next($request);
    }
}
