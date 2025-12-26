<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssigned;

class TaskObserver
{
    protected function actorId()
    {
        try {
            return Auth::id();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function created(Task $task)
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => $this->actorId(),
            'action'  => 'created',
            'details' => json_encode(['attributes' => $task->getAttributes()]),
        ]);
    }

    public function updated(Task $task)
    {
        $changes = $task->getChanges();
        unset($changes['updated_at']);
        if (empty($changes)) {
            return;
        }

        // Check if user_id was changed (task assigned to a user)
        if (isset($changes['user_id']) && $task->user_id) {
            $oldUserId = $task->getOriginal('user_id');
            $newUserId = $task->user_id;
            
            // Only send email if user_id actually changed (not just set to same value)
            // and if there's a valid user assigned
            if ($oldUserId !== $newUserId && $task->user) {
                try {
                    Mail::to($task->user->email)->send(new TaskAssigned($task));
                } catch (\Exception $e) {
                    // Log error but don't break the update process
                    \Log::error('Failed to send task assignment email: ' . $e->getMessage());
                }
            }
        }

        $diff = [];
        foreach ($changes as $key => $value) {
            $diff[$key] = [
                'old' => $task->getOriginal($key),
                'new' => $value,
            ];
        }

        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => $this->actorId(),
            'action'  => 'updated',
            'details' => json_encode(['changes' => $diff]),
        ]);
    }

    public function deleted(Task $task)
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => $this->actorId(),
            'action'  => 'deleted',
            'details' => json_encode(['attributes' => $task->getAttributes()]),
        ]);
    }

    public function restored(Task $task)
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => $this->actorId(),
            'action'  => 'restored',
            'details' => json_encode(['attributes' => $task->getAttributes()]),
        ]);
    }

    public function forceDeleted(Task $task)
    {
        TaskHistory::create([
            'task_id' => $task->id,
            'user_id' => $this->actorId(),
            'action'  => 'force_deleted',
            'details' => json_encode(['attributes' => $task->getAttributes()]),
        ]);
    }
}
