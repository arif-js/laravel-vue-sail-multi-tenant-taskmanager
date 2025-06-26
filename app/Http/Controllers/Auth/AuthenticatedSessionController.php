<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\TeamInvitation;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Invitation handling
        if ($request->has('invite_token') && $request->has('team_id')) {
            $invite = TeamInvitation::where('token', $request->invite_token)
                ->where('team_id', $request->team_id)
                ->first();

            if ($invite) {
                $user = $request->user();

                // Attach user to the team
                $invite->team->users()->syncWithoutDetaching([
                    $user->id => ['role' => $invite->role],
                ]);

                $invite->delete();

                return redirect()->route('teams.show', $invite->team->id)
                    ->with('success', 'You have joined the team!');
            } else {
                return redirect()->route('login')->withErrors([
                    'email' => 'Invalid or expired invitation token.',
                ]);
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
