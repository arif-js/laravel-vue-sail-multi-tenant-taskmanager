<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Task;
use App\Models\Team;

class EnsureUserBelongsToTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $teamParam = $request->route('team');

        // Convert to ID whether it's a model or plain ID
        $teamId = $teamParam instanceof Team ? $teamParam->id : $teamParam;

        if (!$user->teams()->where('teams.id', $teamId)->exists()) {
            abort(403, 'Unauthorized access to this team.');
        }

        return $next($request);
    }
}

