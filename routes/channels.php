<?php
use App\Events\UserAssignedNotification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.assigned.notification.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});

