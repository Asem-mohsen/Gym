<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Contact;
use App\Models\SiteSetting;
use App\Services\RealTimeNotificationService;

class ContactUsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;
    protected $siteSetting;
    protected $realTimeService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact, SiteSetting $siteSetting)
    {
        $this->contact = $contact;
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
            'contact_us',
            'New Contact Us Message - ' . $this->contact->name,
            'A new contact us message has been submitted and requires your attention.',
            url('/admin/contacts/' . $this->contact->id),
            'View Contact Message',
            'normal'
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
            'type' => 'contact_us',
            'priority' => 'normal',
            'subject' => 'New Contact Us Message - ' . $this->contact->name,
            'message' => 'A new contact us message has been submitted and requires your attention.',
            'contact_id' => $this->contact->id,
            'contact_name' => $this->contact->name,
            'contact_email' => $this->contact->email,
            'contact_phone' => $this->contact->phone,
            'contact_subject' => $this->contact->subject,
            'contact_message' => $this->contact->message,
            'is_answered' => $this->contact->is_answered,
            'submitted_at' => $this->contact->created_at->toISOString(),
            'site_setting_id' => $this->siteSetting->id,
            'gym_name' => $this->siteSetting->getTranslation('gym_name', app()->getLocale()),
        ];
    }
}
