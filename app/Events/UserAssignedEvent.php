<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;

class UserAssignedEvent implements ShouldBroadcast
{
    use SerializesModels, InteractsWithSockets;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('user.assigned.'.$this->task->user_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->task->id,
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
            'assigned_by' => auth()->user()?->name ?? 'System',
        ];
    }
}
