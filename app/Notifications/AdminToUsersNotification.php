<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\SiteSetting;
use App\Services\RealTimeNotificationService;

class AdminToUsersNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $siteSetting;
    protected $realTimeService;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data, SiteSetting $siteSetting)
    {
        $this->data = $data;
        $this->siteSetting = $siteSetting;
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
            'admin_message',
            $this->data['subject'],
            $this->data['message'],
            $this->data['action_url'] ?? null,
            $this->data['action_text'] ?? null,
            $this->data['priority'] ?? 'normal'
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
            'type' => 'admin_to_users',
            'subject' => $this->data['subject'],
            'message' => $this->data['message'],
            'details' => $this->data['details'] ?? [],
            'action_url' => $this->data['action_url'] ?? null,
            'action_text' => $this->data['action_text'] ?? null,
            'site_setting_id' => $this->siteSetting->id,
            'gym_name' => $this->siteSetting->getTranslation('gym_name', app()->getLocale()),
            'sent_by' => $this->data['sent_by'] ?? 'Admin',
            'priority' => $this->data['priority'] ?? 'normal',
        ];
    }
}
