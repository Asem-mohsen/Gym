<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\SiteSetting;
use App\Models\Document;
use App\Services\RealTimeNotificationService;

class NewResourceAssignmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $siteSetting;
    protected $assignedBy;
    protected $realTimeService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, SiteSetting $siteSetting, $assignedBy = null)
    {
        $this->document = $document;
        $this->siteSetting = $siteSetting;
        $this->assignedBy = $assignedBy;
        $this->realTimeService = app(RealTimeNotificationService::class);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): array
    {
        $notification = $this->realTimeService->createNotification(
            'new_resource_assignment',
            'New Resource Assigned to Gym - ' . $this->document->title,
            'A new resource has been assigned to your gym and requires your attention.',
            url('/admin/resources/' . $this->document->id),
            'View Resource',
            'high'
        );

        return $notification;
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

}
