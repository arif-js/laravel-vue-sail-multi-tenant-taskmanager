<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;
use App\Models\Team;

class EnsureUserIsLoggedinInTheTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = session('current_team_id');

        if (!$teamId) {
            abort(403, 'Please select a team.');
        }

        if ($teamId !== $request->route('team')->id) {
            abort(403, 'You are not logged in to this team.');
        }

        $teamAvailable = auth()->user()->teams()->where('teams.id', $teamId)->first();

        if (!$teamAvailable) {
            abort(403, 'You are not a member of this team.');
        }

        $tasks = Task::where('team_id', $teamId)->get();
        return $next($request);
    }
}
