<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
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
     * This is the method that tells Laravel which view to use for the email.
     *
     * @return $this
     */
    public function build()
    {
        // Build the reset URL that will be used in the email template
        $resetUrl = route('password.reset', ['token' => $this->token, 'email' => $this->email]);

        // We explicitly tell it to use our 'emails.forgot-password' view
        return $this->subject('Reset Your Password')
            ->view('emails.forgot-password', ['resetUrl' => $resetUrl]);
    }
}
