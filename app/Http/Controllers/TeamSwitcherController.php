<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamSwitcherController extends Controller
{
    public function switch(Request $request, Team $team)
    {
        // Make sure user actually belongs to this team:
        if (! $team->users()->where('user_id', auth()->id())->exists()) {
            abort(403, 'You do not belong to this team.');
        }

        session(['current_team_id' => $team->id]);

        return redirect()->route('dashboard');
    }
}
