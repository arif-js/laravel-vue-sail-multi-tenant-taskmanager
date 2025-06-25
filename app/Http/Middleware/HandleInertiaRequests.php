<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $currentTeam = null;
        if ($user && session('current_team_id')) {
            $currentTeam = $user->teams()
                ->withPivot('user_id')
                ->with('users') // eager load users relation
                ->find(session('current_team_id'));
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'currentTeamId' => fn () => session('current_team_id'),
            'currentTeam' => fn () => $currentTeam,
            'teams' => fn () => $user?->teams()->get(),
        ];
    }
}
