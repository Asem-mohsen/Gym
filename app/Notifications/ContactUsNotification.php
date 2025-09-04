<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Contact;
use App\Models\SiteSetting;

class ContactUsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contact;
    protected $siteSetting;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contact $contact, SiteSetting $siteSetting)
    {
        $this->contact = $contact;
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
            ->subject('New Contact Us Message - ' . $this->contact->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new contact us message has been submitted and requires your attention.')
            ->line('**From:** ' . $this->contact->name)
            ->line('**Email:** ' . $this->contact->email)
            ->line('**Phone:** ' . ($this->contact->phone ?? 'Not provided'))
            ->line('**Subject:** ' . ($this->contact->subject ?? 'No subject'))
            ->line('**Message:** ' . $this->contact->message)
            ->line('**Submitted:** ' . $this->contact->created_at->format('M d, Y H:i'));

        if ($this->contact->is_answered) {
            $mailMessage->line('**Status:** Already answered');
        } else {
            $mailMessage->line('**Status:** Pending response');
        }

        return $mailMessage
            ->action('View Contact Message', url('/admin/contacts/' . $this->contact->id))
            ->line('Please respond to this inquiry promptly to maintain good customer service.');
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
