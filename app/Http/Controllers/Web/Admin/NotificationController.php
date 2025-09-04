<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\SendNotificationRequest;
use App\Services\{NotificationService, RoleService, SiteSettingService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};
use Exception;

class NotificationController extends Controller
{
    protected int $siteSettingId;
    public function __construct(protected NotificationService $notificationService, protected SiteSettingService $siteSettingService, protected RoleService $roleService)
    {
        $this->notificationService = $notificationService;
        $this->siteSettingService = $siteSettingService;
        $this->roleService = $roleService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Display the notification management page
     */
    public function index()
    {
        try {
            return view('admin.notifications.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading notification dashboard. Please try again.');
        }
    }

    /**
     * Show the form for sending a new notification
     */
    public function create()
    {
        try {
            $roles = $this->roleService->getRoles(except: ['admin','master_admin']);
            return view('admin.notifications.create', compact('roles'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading notification form. Please try again.');
        }
    }

    /**
     * Send a new notification
     */
    public function store(SendNotificationRequest $request)
    {
        try {
            $data = $request->validated();
            $data['sent_by'] = Auth::user()->name;
            
            $success = $this->notificationService->sendAdminToUsersNotification($data, $this->siteSettingId);
            
            if ($success) {
                return redirect()->route('admin.notifications.index')->with('success', 'Notification sent successfully to ' . count($data['target_roles']) . ' role(s).');
            } else {
                return redirect()->back()->with('error', 'Failed to send notification. Please try again.')->withInput();
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error sending notification. Please try again.')->withInput();
        }
    }

    /**
     * Display notification history
     */
    public function history(Request $request)
    {
        try {
            $filters = $request->only(['priority', 'type', 'read_status', 'date_from', 'date_to']);
            $notifications = $this->notificationService->getNotificationsBySite($this->siteSettingId, $filters);
            
            return view('admin.notifications.history', compact('notifications', 'filters'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading notification history. Please try again.');
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $success = $this->notificationService->markAsRead(Auth::user(), $id);
            
            if ($request->ajax()) {
                return response()->json(['success' => $success]);
            }
            
            return redirect()->back()->with('success', 'Notification marked as read.');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error marking notification as read.']);
            }
            
            return redirect()->back()->with('error', 'Error marking notification as read.');
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $success = $this->notificationService->markAllAsRead(Auth::user());
            
            if ($request->ajax()) {
                return response()->json(['success' => $success]);
            }
            
            return redirect()->back()->with('success', 'All notifications marked as read.');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error marking notifications as read.']);
            }
            
            return redirect()->back()->with('error', 'Error marking notifications as read.');
        }
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, $id)
    {
        try {
            $success = $this->notificationService->deleteNotification(Auth::user(), $id);
            
            if ($request->ajax()) {
                return response()->json(['success' => $success]);
            }
            
            return redirect()->back()->with('success', 'Notification deleted successfully.');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error deleting notification.']);
            }
            
            return redirect()->back()->with('error', 'Error deleting notification.');
        }
    }

    /**
     * Get current user's notifications for sidebar
     */
    public function getUserNotifications()
    {
        try {
            $notifications = $this->notificationService->getUserNotifications(Auth::user(), 5);
            $unreadCount = $this->notificationService->getUnreadCount(Auth::user());
            
            return response()->json([
                'notifications' => $notifications->items(),
                'unread_count' => $unreadCount
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error loading notifications.'], 500);
        }
    }

    /**
     * Get recent notifications for admin sidebar
     */
    public function recent()
    {
        try {
            $notifications = $this->notificationService->getRecentlySentNotifications($this->siteSettingId, 5);
            $unreadCount = $this->notificationService->getUnreadCount(Auth::user());
            
            Log::info('Recent notifications:', ['notifications' => $notifications, 'unread_count' => $unreadCount]);
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications,
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

    /**
     * Get recent sent notifications by type
     */
    public function recentSentByType()
    {
        try {
            $notifications = $this->notificationService->getRecentSentNotificationsByType($this->siteSettingId, 5);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications->values()
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error loading recent sent notifications by type', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error loading recent sent notifications.'
            ], 500);
        }
    }

    /**
     * Get recent system notifications
     */
    public function recentSystem()
    {
        try {
            $notifications = $this->notificationService->getRecentSystemNotifications($this->siteSettingId, 5);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'notifications' => $notifications
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error loading recent system notifications', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error loading recent system notifications.'
            ], 500);
        }
    }
}
