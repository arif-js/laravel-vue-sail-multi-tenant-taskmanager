<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TeamInvitation;

class TeamInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(TeamInvitation $invitation, string $inviteLink)
    {
        $this->invitation = $invitation;
        $this->inviteLink = $inviteLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join a team',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.team_invite',
            with: [
                'teamName' => $this->invitation->team->name,
                'acceptUrl' => $this->inviteLink,
                'role' => $this->invitation->role,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
