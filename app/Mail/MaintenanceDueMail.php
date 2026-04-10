<?php

namespace App\Mail;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MaintenanceDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vehicle;
    public $user;
    public $currentKm;
    public $lastMaintenanceKm;
    public $nextDueKm;
    public $lastMaintenanceType;
    public $timestamp;

    /**
     * Create a new message instance.
     */
    public function __construct(Vehicle $vehicle, $currentKm, $lastMaintenanceKm, $nextDueKm, $lastMaintenanceType)
    {
        $this->vehicle = $vehicle;
        $this->user = $vehicle->bm;
        $this->currentKm = $currentKm;
        $this->lastMaintenanceKm = $lastMaintenanceKm;
        $this->nextDueKm = $nextDueKm;
        $this->lastMaintenanceType = $lastMaintenanceType;
        $this->timestamp = now()->format('Y-m-d H:i:s');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ PREVENTIVE MAINTENANCE DUE - ' . $this->vehicle?->plate_number . ' - Vehicle Monitoring System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.maintenance-due',
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
