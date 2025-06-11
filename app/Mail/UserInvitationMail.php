<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Build the reset URL that will be used in the email template
        $resetUrl = route('password.reset', ['token' => $this->token, 'email' => $this->email]);

        return $this->subject('Reset Your Password')
            ->view('emails.forgot-password', ['resetUrl' => $resetUrl]);
    }
}
