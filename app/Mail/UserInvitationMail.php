<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * We use PHP 8's constructor property promotion for cleaner code.
     * This makes the $invitation object automatically available to our view.
     */
    public function __construct(
        public string $invitationUrl
    ) {}

    /**
     * Get the message envelope.
     * This defines the subject line of the email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You Have Been Invited to Join the Team',
        );
    }

    /**
     * Get the message content definition.
     * This tells Laravel which view file to use for the email's body.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
        );
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

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('You Have Been Invited to Join the Team')
            ->view('emails.invitation-email');
    }
}
