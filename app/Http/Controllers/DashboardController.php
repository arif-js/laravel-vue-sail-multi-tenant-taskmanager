<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboardService)
    {
        return Inertia::render('Dashboard', [
            'teams' => $dashboardService->userTeams(),
        ]);
    }
}

