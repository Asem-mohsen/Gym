<?php

namespace App\Mail;

use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class GymDeactivationDataExport extends Mailable
{
    use Queueable, SerializesModels;

    public $gym;
    public $excelFilePath;

    /**
     * Create a new message instance.
     */
    public function __construct(SiteSetting $gym, string $excelFilePath)
    {
        $this->gym = $gym;
        $this->excelFilePath = $excelFilePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Gym Deactivation - Your Data Export',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.gym_deactivation_data_export',
            with: [
                'gym' => $this->gym,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->excelFilePath)
                ->as('gym_data_export_' . $this->gym->id . '.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
