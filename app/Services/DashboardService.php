<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    protected User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?? Auth::user();
    }

    public function userTeams(): array
    {
        $teams = $this->user->teams;

        if ($teams->isEmpty()) {
            return [];
        }

        // Return stats for each team
        return $teams->map(function ($team) {
            $query = Task::where('team_id', $team->id);

            return [
                'team_id' => $team->id,
                'team_name' => $team->name,
                'total' => $query->count(),
                'pending' => (clone $query)->where('status', 'pending')->count(),
                'in_progress' => (clone $query)->where('status', 'in_progress')->count(),
                'completed' => (clone $query)->where('status', 'completed')->count(),
            ];
        })->toArray();
    }
}
