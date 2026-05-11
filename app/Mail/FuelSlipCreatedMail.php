<?php

namespace App\Mail;

use App\Models\FuelSlip;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FuelSlipCreatedMail extends Mailable
{
    use SerializesModels;

    public $fuelSlip;
    public $vehicle;
    public $user;
    public $timestamp;

    /**
     * Create a new message instance.
     */
    public function __construct(FuelSlip $fuelSlip)
    {
        $this->fuelSlip = $fuelSlip;
        $this->vehicle = $fuelSlip->vehicle;
        $this->user = $fuelSlip->user;
        $this->timestamp = now()->format('Y-m-d H:i:s');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Fuel Slip Recorded - ' . $this->vehicle?->plate_number . ' - Vehicle Monitoring System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.fuel-slip-created',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
