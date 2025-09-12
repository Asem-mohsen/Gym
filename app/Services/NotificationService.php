<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\{User, Notification, SiteSetting, Subscription, Document, Contact};
use App\Notifications\{AdminToUsersNotification, MembershipExpirationNotification, NewResourceAssignmentNotification, ContactUsNotification};
use App\Services\RealTimeNotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class NotificationService
{
    protected $realTimeService;

    public function __construct(RealTimeNotificationService $realTimeService)
    {
        $this->realTimeService = $realTimeService;
    }
    /**
     * Send notification from admin to users with specific roles
     */
    public function sendAdminToUsersNotification(array $data, int $siteSettingId): bool
    {
        try {
            DB::beginTransaction();

            $users = $this->getUsersByRoles($siteSettingId, $data['target_roles'] ?? ['regular_user']);

            Log::info('Notification sending attempt', [
                'site_setting_id' => $siteSettingId,
                'target_roles' => $data['target_roles'] ?? ['regular_user'],
                'users_found' => $users->count(),
                'user_emails' => $users->pluck('email')->toArray()
            ]);

            if ($users->isEmpty()) {
                Log::warning('No users found for notification', [
                    'site_setting_id' => $siteSettingId,
                    'target_roles' => $data['target_roles'] ?? ['regular_user']
                ]);
                return false;
            }

            $siteSetting = SiteSetting::findOrFail($siteSettingId);

            // Send notification to each user
            foreach ($users as $user) {
                $user->notify(new AdminToUsersNotification($data, $siteSetting));
            }

            // Broadcast real-time notification to all users
            $notification = $this->realTimeService->createNotification(
                'admin_message',
                $data['subject'],
                $data['message'],
                $data['action_url'] ?? null,
                $data['action_text'] ?? null,
                $data['priority'] ?? 'normal'
            );

            $this->realTimeService->broadcastToAll($notification);

            // Log the notification
            Log::info('Admin notification sent to users', [
                'site_setting_id' => $siteSettingId,
                'target_roles' => $data['target_roles'] ?? ['regular_user'],
                'user_count' => $users->count(),
                'subject' => $data['subject'],
                'sent_by' => $data['sent_by'] ?? 'Admin'
            ]);

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to send admin notification', [
                'error' => $e->getMessage(),
                'site_setting_id' => $siteSettingId,
                'data' => $data
            ]);
            return false;
        }
    }

    /**
     * Send membership expiration notifications to admins
     */
    public function sendMembershipExpirationNotifications(SiteSetting $siteSetting): bool
    {
        try {
            DB::beginTransaction();

            $expiringSubscriptions = $this->getExpiringSubscriptions($siteSetting->id);

            if ($expiringSubscriptions->isEmpty()) {
                return true;
            }

            $admins = $this->getUsersByRoles($siteSetting->id, ['admin', 'management']);

            if ($admins->isEmpty()) {
                return false;
            }

            $notificationCount = 0;

            foreach ($expiringSubscriptions as $subscription) {
                $daysUntilExpiry = Carbon::parse($subscription->expires_at)->diffInDays(now(), false);

                // Only send notifications for subscriptions expiring in the next 7 days or already expired
                if ($daysUntilExpiry <= 7) {
                    foreach ($admins as $admin) {
                        $admin->notify(new MembershipExpirationNotification($subscription, $siteSetting, $daysUntilExpiry));
                        $notificationCount++;
                    }
                }
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to send membership expiration notifications', [
                'error' => $e->getMessage(),
                'site_setting_id' => $siteSetting->id
            ]);
            return false;
        }
    }

    /**
     * Send new resource assignment notification to admins
     */
    public function sendNewResourceAssignmentNotification(Document $document, SiteSetting $siteSetting, ?User $assignedBy = null): bool
    {
        try {
            DB::beginTransaction();

            $admins = $this->getUsersByRoles($siteSetting->id, ['admin', 'management']);

            if ($admins->isEmpty()) {
                return false;
            }

            $notificationCount = 0;

            foreach ($admins as $admin) {
                $admin->notify(new NewResourceAssignmentNotification($document, $siteSetting, $assignedBy));
                $notificationCount++;
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to send resource assignment notification', [
                'error' => $e->getMessage(),
                'site_setting_id' => $siteSetting->id,
                'document_id' => $document->id
            ]);
            return false;
        }
    }

    /**
     * Send contact us notification to sales users
     */
    public function sendContactUsNotification(Contact $contact, SiteSetting $siteSetting): bool
    {
        try {
            DB::beginTransaction();

            $salesUsers = $this->getUsersByRoles($siteSetting->id, ['sales']);

            if ($salesUsers->isEmpty()) {
                return false;
            }

            $notificationCount = 0;

            foreach ($salesUsers as $salesUser) {
                $salesUser->notify(new ContactUsNotification($contact, $siteSetting));
                $notificationCount++;
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to send contact us notification', [
                'error' => $e->getMessage(),
                'site_setting_id' => $siteSetting->id,
                'contact_id' => $contact->id
            ]);
            return false;
        }
    }

    /**
     * Get users by roles for a specific gym
     */
    private function getUsersByRoles(int $siteSettingId, array $roles): Collection
    {
        return User::whereHas('gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->where('status', 'active')
            ->get();
    }

    /**
     * Get subscriptions expiring soon
     */
    private function getExpiringSubscriptions(int $siteSettingId): Collection
    {
        return Subscription::whereHas('branch', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays(7))
            ->with(['user', 'membership', 'branch'])
            ->get();
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(User $user, int $limit = 20, int $page = 1): LengthAwarePaginator
    {
        return $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Get unread notification count for user
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead(User $user): bool
    {
        return $user->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Delete notification
     */
    public function deleteNotification(User $user, string $notificationId): bool
    {
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            return $notification->delete();
        }

        return false;
    }

    /**
     * Get notifications by site with filtering
     */
    public function getNotificationsBySite(int $siteSettingId, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Notification::where('site_setting_id', $siteSettingId);

        // Apply filters
        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['read_status'])) {
            if ($filters['read_status'] === 'read') {
                $query->whereNotNull('read_at');
            } else {
                $query->whereNull('read_at');
            }
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get count of expired notifications
     */
    public function getExpiredNotificationsCount(): int
    {
        return Notification::whereNotNull('expires_at')->where('expires_at', '<', now())->count();
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpiredNotifications(): int
    {
        $deletedCount = Notification::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->delete();

        return $deletedCount;
    }


    /**
     * Get recently sent notifications for a site (admin view)
     */
    public function getRecentlySentNotifications(int $siteSettingId, int $limit = 5): Collection
    {
        return Notification::whereHas('notifiable', function ($query) use ($siteSettingId) {
                $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                    $gymQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent sent notifications grouped by type/subject (one from each type)
     */
    public function getRecentSentNotificationsByType(int $siteSettingId, int $limit = 5): Collection
    {
        return Notification::whereHas('notifiable', function ($query) use ($siteSettingId) {
                $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                    $gymQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->whereIn('type', [
                'App\Notifications\AdminToUsersNotification'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type')
            ->map(function ($notifications) {
                return $notifications->first();
            })
            ->take($limit);
    }

    /**
     * Get recent system notifications (from users to admins)
     */
    public function getRecentSystemNotifications(int $siteSettingId, int $limit = 5): Collection
    {
        return Notification::whereHas('notifiable', function ($query) use ($siteSettingId) {
                $query->whereHas('gyms', function ($gymQuery) use ($siteSettingId) {
                    $gymQuery->where('site_setting_id', $siteSettingId);
                });
            })
            ->whereIn('type', [
                'App\Notifications\NewResourceAssignmentNotification',
                'App\Notifications\ContactUsNotification',
                'App\Notifications\MembershipExpirationNotification'
            ])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
