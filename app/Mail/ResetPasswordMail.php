<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $userName;
    public $userEmail;

    public function __construct($resetUrl, $userEmail, $userName = null)
    {
        $this->resetUrl = $resetUrl;
        $this->userEmail = $userEmail;
        $this->userName = $userName ?? 'User';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->userEmail,
            subject: 'Password Reset Request',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'userName' => $this->userName,
            ],
        );
    }
}
