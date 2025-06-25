<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\TeamInvitation;
use Inertia\Inertia;
use App\Mail\TeamInviteMail;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    public function create()
    {
        return Inertia::render('Teams/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team = Team::create(([
            'name' => $request->name,
            'owner_id' => auth()->id(),
        ]));

        $team->users()->attach(auth()->id(), ['role' => 'Owner']);

        return redirect()->route('dashboard')->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        return Inertia::render('Teams/Show', [
            'team' => $team->load('owner', 'users'),
            'tasksCount' => $team->tasks()->count(),
            'role' => $team->users()->where('user_id', auth()->id())->first()->pivot->role ?? 'Member',
        ]);
    }

    public function edit(Team $team)
    {
        return Inertia::render('Teams/Edit', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $team->update($request->only('name'));

        return redirect()->route('teams.show', $team)->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        \Log::info('Deleting team: ' . $team->id);
        $team->delete();

        return redirect()->route('dashboard')->with('success', 'Team deleted successfully.');
    }

    public function showInviteForm(Team $team)
    {
        return Inertia::render('Teams/Invite', [
            'team' => $team,
        ]);
    }

    public function invite(Request $request, Team $team)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:Member,Admin',
        ]);

        $token = bin2hex(random_bytes(16)); // Generate a secure random token

        $invitation = TeamInvitation::updateOrCreate(
            ['team_id' => $team->id, 'email' => $request->email],
            ['token' => $token, 'role' => $request->role]
        );

        $invitation->load('team');

        // ✅ Check if this email is already a registered user:
        $userExists = User::where('email', $request->email)->exists();

        // ✅ Choose the appropriate link:
        if ($userExists) {
            $inviteLink = route('login', [
                'invite_token' => $token,
                'team_id' => $team->id,
            ]);
        } else {
            $inviteLink = route('register', [
                'invite_token' => $token,
                'team_id' => $team->id,
            ]);
        }

        // ✅ Send mail and pass the link:
        Mail::to($request->email)->send(new TeamInviteMail($invitation, $inviteLink));

        return redirect()->route('teams.show', $team)
            ->with('success', 'Invitation sent successfully.');
    }

    public function accept(Team $team, $token)
    {
        $invite = TeamInvitation::where('token', $token)
            ->where('team_id', $team->id)
            ->firstOrFail();

        $user = auth()->user();

        if (!$user) {
            return redirect()->route('register', [
                'invite_token' => $token,
                'team_id' => $team->id,
            ]);
        }

        $team->users()->syncWithoutDetaching([
            $user->id => ['role' => $invite->role],
        ]);

        $invite->delete();

        return redirect()->route('teams.show', $team->id)
            ->with('success', 'You have joined the team!');
    }

}
