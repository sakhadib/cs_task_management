<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $taskUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->taskUrl = url('/tasks/' . $task->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Task Assigned: ' . $this->task->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-assigned',
            with: [
                'taskTitle' => $this->task->title,
                'taskDescription' => $this->task->description,
                'taskState' => $this->getStateLabel($this->task->state),
                'assignedBy' => $this->task->creator ? $this->task->creator->name : 'System',
                'teamName' => $this->task->team ? $this->task->team->name : 'No Team',
                'panelName' => $this->task->panel ? $this->task->panel->name : 'No Panel',
                'taskUrl' => $this->taskUrl,
                'userName' => $this->task->user->name,
            ],
        );
    }

    /**
     * Get human-readable state label
     */
    private function getStateLabel($state)
    {
        $labels = [
            'pending assignment' => 'Pending Assignment',
            'team assigned' => 'Team Assigned',
            'assigned to user' => 'Assigned to User',
            'reassigned to user' => 'Reassigned to User',
            'working' => 'Working',
            'submitted to review' => 'Submitted to Review',
            'completed' => 'Completed',
        ];

        return $labels[$state] ?? ucfirst(str_replace('_', ' ', $state));
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
