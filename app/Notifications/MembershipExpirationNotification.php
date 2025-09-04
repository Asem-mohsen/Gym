<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Subscription;
use App\Models\SiteSetting;

class MembershipExpirationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    protected $siteSetting;
    protected $daysUntilExpiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, SiteSetting $siteSetting, int $daysUntilExpiry)
    {
        $this->subscription = $subscription;
        $this->siteSetting = $siteSetting;
        $this->daysUntilExpiry = $daysUntilExpiry;
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
        $user = $this->subscription->user;
        $membership = $this->subscription->membership;
        $branch = $this->subscription->branch;

        $subject = $this->daysUntilExpiry === 0 
            ? 'URGENT: Membership Expired Today - ' . $user->name
            : 'Membership Expiring Soon - ' . $user->name;

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->daysUntilExpiry === 0) {
            $mailMessage->line('**URGENT:** A membership has expired today and requires immediate attention.')
                ->line('**Member:** ' . $user->name . ' (' . $user->email . ')')
                ->line('**Membership:** ' . $membership->getTranslation('name', app()->getLocale()))
                ->line('**Branch:** ' . $branch->getTranslation('name', app()->getLocale()))
                ->line('**Expired Date:** ' . now()->format('M d, Y'));
        } else {
            $mailMessage->line('A membership will expire soon and may need attention.')
                ->line('**Member:** ' . $user->name . ' (' . $user->email . ')')
                ->line('**Membership:** ' . $membership->getTranslation('name', app()->getLocale()))
                ->line('**Branch:** ' . $branch->getTranslation('name', app()->getLocale()))
                ->line('**Expires In:** ' . $this->daysUntilExpiry . ' day(s)')
                ->line('**Expiry Date:** ' . now()->addDays($this->daysUntilExpiry)->format('M d, Y'));
        }

        return $mailMessage
            ->action('View Member Details', url('/admin/users/' . $user->id))
            ->line('Please take appropriate action to ensure member retention.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $user = $this->subscription->user;
        $membership = $this->subscription->membership;
        $branch = $this->subscription->branch;

        return [
            'type' => 'membership_expiration',
            'priority' => $this->daysUntilExpiry === 0 ? 'urgent' : 'high',
            'subject' => $this->daysUntilExpiry === 0 
                ? 'URGENT: Membership Expired Today - ' . $user->name
                : 'Membership Expiring Soon - ' . $user->name,
            'message' => $this->daysUntilExpiry === 0 
                ? 'A membership has expired today and requires immediate attention.'
                : 'A membership will expire soon and may need attention.',
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'subscription_id' => $this->subscription->id,
            'membership_name' => $membership->getTranslation('name', app()->getLocale()),
            'branch_name' => $branch->getTranslation('name', app()->getLocale()),
            'days_until_expiry' => $this->daysUntilExpiry,
            'expiry_date' => now()->addDays($this->daysUntilExpiry)->toISOString(),
            'site_setting_id' => $this->siteSetting->id,
            'gym_name' => $this->siteSetting->getTranslation('gym_name', app()->getLocale()),
        ];
    }
}
