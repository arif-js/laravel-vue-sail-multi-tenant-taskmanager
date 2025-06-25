<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create 2 users
        $owner = User::factory()->create([
            'name' => 'Owner User',
            'email' => 'owner@example.com',
        ]);

        $member = User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@example.com',
        ]);

        // Create 1 team, owned by the first user
        $team = Team::factory()->create([
            'name' => 'Demo Team',
            'owner_id' => $owner->id,
        ]);

        // Attach both users to the team with roles
        $team->users()->attach($owner->id, ['role' => 'Owner']);
        $team->users()->attach($member->id, ['role' => 'Member']);

        // Create 1 task for that team
        Task::factory()->create([
            'team_id' => $team->id,
            'user_id' => $owner->id, // assigned to owner for example
            'status' => 'pending',
            'title' => 'First Task',
            'description' => 'This is a seeded task.',
        ]);
    }
}
