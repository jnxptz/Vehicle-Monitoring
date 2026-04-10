<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BudgetAdjustmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $adjustmentAmount;
    public $oldBudget;
    public $newBudget;
    public $type;
    public $timestamp;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $adjustmentAmount, $oldBudget, $newBudget, $type)
    {
        $this->user = $user;
        $this->adjustmentAmount = $adjustmentAmount;
        $this->oldBudget = $oldBudget;
        $this->newBudget = $newBudget;
        $this->type = $type;
        $this->timestamp = now()->format('Y-m-d H:i:s');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'increase' 
            ? 'Budget Increase Notification - Vehicle Monitoring System'
            : 'Budget Decrease Notification - Vehicle Monitoring System';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.budget-adjustment',
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
}
