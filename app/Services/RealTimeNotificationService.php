<?php

namespace App\Services;

use Exception;
use App\Events\NotificationSentEvent;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RealTimeNotificationService
{
    /**
     * Send a real-time notification to all users
     */
    public function broadcastToAll(array $notification): void
    {
        try {
            broadcast(new NotificationSentEvent($notification));
        } catch (Exception $e) {
            Log::error('Failed to broadcast real-time notification', [
                'error' => $e->getMessage(),
                'notification' => $notification
            ]);
        }
    }

    /**
     * Send a real-time notification to specific users
     */
    public function broadcastToUsers(array $userIds, array $notification): void
    {
        try {
            // For now, we'll broadcast to all and let the frontend filter
            // In a more advanced setup, you'd use private channels
            $notification['target_users'] = $userIds;
            broadcast(new NotificationSentEvent($notification));
            
        } catch (Exception $e) {
            Log::error('Failed to broadcast real-time notification to users', [
                'error' => $e->getMessage(),
                'user_ids' => $userIds,
                'notification' => $notification
            ]);
        }
    }

    /**
     * Send a real-time notification to admin users
     */
    public function broadcastToAdmins(array $notification): void
    {
        try {
            $notification['target_audience'] = 'admins';
            broadcast(new NotificationSentEvent($notification));
            
        } catch (Exception $e) {
            Log::error('Failed to broadcast real-time notification to admins', [
                'error' => $e->getMessage(),
                'notification' => $notification
            ]);
        }
    }

    /**
     * Create a notification array with proper structure
     */
    public function createNotification(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionText = null,
        string $priority = 'normal'
    ): array {
        return [
            'id' => uniqid(),
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'action_text' => $actionText,
            'priority' => $priority,
            'icon' => $this->getIconForType($type),
            'color' => $this->getColorForPriority($priority),
        ];
    }

    /**
     * Get appropriate icon for notification type
     */
    private function getIconForType(string $type): string
    {
        return match ($type) {
            'success' => 'check-circle',
            'error' => 'exclamation-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'information-circle',
            'booking' => 'calendar',
            'payment' => 'credit-card',
            'locker' => 'lock-closed',
            'class' => 'academic-cap',
            default => 'bell'
        };
    }

    /**
     * Get appropriate color for priority
     */
    private function getColorForPriority(string $priority): string
    {
        return match ($priority) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'blue',
            default => 'gray'
        };
    }
}
