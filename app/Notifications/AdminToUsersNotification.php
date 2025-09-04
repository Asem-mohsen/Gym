<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SiteSetting;

class AdminToUsersNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $siteSetting;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data, SiteSetting $siteSetting)
    {
        $this->data = $data;
        $this->siteSetting = $siteSetting;
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
            ->subject($this->data['subject'])
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($this->data['message']);

        // Add additional details if provided
        if (isset($this->data['details'])) {
            foreach ($this->data['details'] as $detail) {
                $mailMessage->line($detail);
            }
        }

        // Add action button if URL is provided
        if (isset($this->data['action_url']) && isset($this->data['action_text'])) {
            $mailMessage->action($this->data['action_text'], $this->data['action_url']);
        }

        return $mailMessage
            ->line('Thank you for being a valued member of ' . $this->siteSetting->getTranslation('gym_name', app()->getLocale()) . '!');
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
