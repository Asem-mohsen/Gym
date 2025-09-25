<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $perPage = $request->get('per_page', 20);
            
            $notifications = $this->notificationService->getUserNotifications($user, $perPage);
            
            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading notifications.'
            ], 500);
        }
    }

    /**
     * Get unread notification count
     */
    public function unreadCount()
    {
        try {
            $user = Auth::user();
            $count = $this->notificationService->getUnreadCount($user);
            
            return response()->json([
                'success' => true,
                'data' => ['count' => $count]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting unread count.'
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $success = $this->notificationService->markAsRead($user, $id);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification marked as read.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to mark notification as read.'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read.'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            $success = $this->notificationService->markAllAsRead($user);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'All notifications marked as read.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to mark notifications as read.'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read.'
            ], 500);
        }
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();

            $success = $this->notificationService->deleteNotification($user, $id);
            
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification deleted successfully.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete notification.'
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notification.'
            ], 500);
        }
    }

    /**
     * Get recent notifications for navbar dropdown
     */
    public function recent()
    {
        try {
            $user = Auth::user();
            $notifications = $this->notificationService->getUserNotifications($user, 5);
            $unreadCount = $this->notificationService->getUnreadCount($user);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications->items(),
                    'unread_count' => $unreadCount
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading recent notifications.'
            ], 500);
        }
    }
}
