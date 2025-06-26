<?php
use App\Events\UserAssignedNotification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.assigned.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId;
});