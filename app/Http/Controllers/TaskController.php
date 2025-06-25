<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Team $team)
    {
        $tasks = Task::where('team_id', $team->id)
            ->with('user')
            ->get();

        return Inertia::render('Tasks/Index', [
            'team' => $team,
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request, Team $team)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        Task::create([
            'team_id' => $team->id,
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('user')->where('team_id', $team->id)->get(),
        ]);
    }

    public function update(Request $request, Team $team, Task $task)
    {
        // Validate if specific fields are present
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'user_id' => 'sometimes|exists:users,id', // optional future support
        ]);

        $task->update($validated);

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('user')->where('team_id', $team->id)->get()
    ]);
    }

    public function destroy(Team $team, Task $task)
    {
        $task->delete();

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('user')->where('team_id', $team->id)->get(),
            'deletedTask' => $task,
        ]);
    }
}
