<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SiteSetting;
use App\Models\Document;

class NewResourceAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $siteSetting;
    protected $assignedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, SiteSetting $siteSetting, $assignedBy = null)
    {
        $this->document = $document;
        $this->siteSetting = $siteSetting;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('New Resource Assigned to Gym - ' . $this->document->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new resource has been assigned to your gym and requires your attention.')
            ->line('**Resource Title:** ' . $this->document->title)
            ->line('**Resource Type:** ' . $this->document->type)
            ->line('**Description:** ' . ($this->document->description ?? 'No description provided'))
            ->line('**File Size:** ' . $this->formatFileSize($this->document->size))
            ->line('**Assigned Date:** ' . now()->format('M d, Y H:i'));

        if ($this->assignedBy) {
            $mailMessage->line('**Assigned By:** ' . $this->assignedBy->name);
        }

        if ($this->document->is_internal) {
            $mailMessage->line('**Note:** This is an internal resource for staff use only.');
        }

        return $mailMessage
            ->action('View Resource', url('/admin/resources/' . $this->document->id))
            ->line('Please review and take any necessary actions for this new resource.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_resource_assignment',
            'priority' => 'high',
            'subject' => 'New Resource Assigned to Gym - ' . $this->document->title,
            'message' => 'A new resource has been assigned to your gym and requires your attention.',
            'document_id' => $this->document->id,
            'document_title' => $this->document->title,
            'document_type' => $this->document->type,
            'document_description' => $this->document->description,
            'file_size' => $this->document->size,
            'is_internal' => $this->document->is_internal,
            'assigned_by' => $this->assignedBy ? $this->assignedBy->name : 'System',
            'assigned_date' => now()->toISOString(),
            'site_setting_id' => $this->siteSetting->id,
            'gym_name' => $this->siteSetting->getTranslation('gym_name', app()->getLocale()),
        ];
    }

    /**
     * Format file size for display
     */
    private function formatFileSize($bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
