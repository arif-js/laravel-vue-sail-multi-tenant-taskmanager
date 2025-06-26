<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamSwitcherController;

use App\Models\Team;
use App\Models\Task;
use App\Models\User;
use App\Http\Middleware\EnsureUserBelongsToTeam;
use App\Http\Middleware\EnsureUserIsTeamAdminOrOwner;
use App\Http\Middleware\EnsureUserIsTeamOwner;
use App\Http\Middleware\RedirectAuthenticatedInvite;
use App\Http\Middleware\EnsureUserIsLoggedinInTheTeam;
use App\Events\UserAssignedNotification;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->middleware('guest', RedirectAuthenticatedInvite::class)->name('login');

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->middleware('guest', RedirectAuthenticatedInvite::class)->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('dashboard')->middleware(['verified'])->group(function () {
        Route::get('/', [DashboardController::class, '__invoke'])->name('dashboard');

        Route::resource('teams', TeamController::class)->except(['index']);

        Route::prefix('teams/{team}')->middleware(EnsureUserBelongsToTeam::class)->group(function () {
            Route::resource('tasks', TaskController::class)
                ->middleware(EnsureUserIsLoggedinInTheTeam::class);

            Route::get('/invite-form', [TeamController::class, 'showInviteForm'])
                ->middleware([EnsureUserBelongsToTeam::class, EnsureUserIsTeamAdminOrOwner::class])
                ->name('teams.inviteForm');

            Route::get('/accept/{token}', [TeamController::class, 'accept'])->name('teams.accept');

            Route::post('/invite', [TeamController::class, 'invite'])
                ->middleware(EnsureUserIsTeamAdminOrOwner::class)
                ->name('teams.invite');

            Route::put('/', [TeamController::class, 'update'])
                ->middleware(EnsureUserIsTeamAdminOrOwner::class)
                ->name('teams.update');

            Route::delete('/', [TeamController::class, 'destroy'])
                ->middleware(EnsureUserIsTeamOwner::class)
                ->name('teams.destroy');

            Route::post('/switch', [TeamSwitcherController::class, 'switch'])
                ->name('teams.switch');
        });
    });
});

require __DIR__.'/auth.php';
