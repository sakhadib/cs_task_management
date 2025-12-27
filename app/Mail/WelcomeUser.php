<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->loginUrl = url('/');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to IUTCS Internal System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-user',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'userRole' => $this->getRoleLabel($this->user->role),
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }

    /**
     * Get human-readable role label
     */
    private function getRoleLabel($role)
    {
        $labels = [
            'admin' => 'Administrator',
            'advisor' => 'Advisor',
            'member' => 'Member',
            'allumni' => 'Alumni',
            'guest' => 'Guest',
        ];

        return $labels[$role] ?? ucfirst($role);
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
