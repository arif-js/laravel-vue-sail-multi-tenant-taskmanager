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

    public function create(Team $team)
    {
        $users = $team->users;

        return Inertia::render('Tasks/Create', [
            'team' => $team,
            'users' => $users,
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

        return redirect()->route('tasks.index', $team->id)
            ->with('success', 'Task created.');
    }

    public function edit(Team $team, Task $task)
    {
        $users = $team->users;

        return Inertia::render('Tasks/Edit', [
            'team' => $team,
            'task' => $task,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Team $team, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($request->only('title', 'description', 'status', 'user_id'));

        return redirect()->route('tasks.index', $team->id)
            ->with('success', 'Task updated.');
    }

    public function destroy(Team $team, Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index', $team->id)
            ->with('success', 'Task deleted.');
    }
}
