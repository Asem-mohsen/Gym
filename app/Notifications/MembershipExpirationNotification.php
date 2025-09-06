<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Subscription;
use App\Models\SiteSetting;
use App\Services\RealTimeNotificationService;

class MembershipExpirationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;
    protected $siteSetting;
    protected $daysUntilExpiry;
    protected $realTimeService;

    /**
     * Create a new notification instance.
     */
    public function __construct(Subscription $subscription, SiteSetting $siteSetting, int $daysUntilExpiry)
    {
        $this->subscription = $subscription;
        $this->siteSetting = $siteSetting;
        $this->daysUntilExpiry = $daysUntilExpiry;
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
        $user = $this->subscription->user;
        $priority = $this->daysUntilExpiry === 0 ? 'urgent' : 'high';
        $title = $this->daysUntilExpiry === 0 
            ? 'URGENT: Membership Expired Today - ' . $user->name
            : 'Membership Expiring Soon - ' . $user->name;
        $message = $this->daysUntilExpiry === 0 
            ? 'A membership has expired today and requires immediate attention.'
            : 'A membership will expire soon and may need attention.';

        $notification = $this->realTimeService->createNotification(
            'membership_expiration',
            $title,
            $message,
            url('/admin/users/' . $user->id),
            'View Member Details',
            $priority
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
