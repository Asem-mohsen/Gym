<?php

namespace App\Notifications;

use App\Models\BranchScoreReviewRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Services\RealTimeNotificationService;

class ReviewRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reviewRequest;
    protected $realTimeService;

    /**
     * Create a new notification instance.
     */
    public function __construct(BranchScoreReviewRequest $reviewRequest)
    {
        $this->reviewRequest = $reviewRequest;
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
        $branchScore = $this->reviewRequest->branchScore;
        $branch = $branchScore->branch;
        $status = $this->reviewRequest->status;
        
        $title = 'Review Request ' . ucfirst($status) . ' - ' . $branch->getTranslation('name', app()->getLocale());
        $message = $status === 'approved' 
            ? 'Great news! Your review request has been approved.'
            : ($status === 'rejected' 
                ? 'Your review request has been rejected.'
                : 'Your review request is still pending review.');

        $notification = $this->realTimeService->createNotification(
            'review_request_status',
            $title,
            $message,
            url('/admin/score-management/review-requests/' . $this->reviewRequest->id),
            'View Review Request',
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
        $branchScore = $this->reviewRequest->branchScore;
        $branch = $branchScore->branch;

        return [
            'type' => 'review_request_status',
            'review_request_id' => $this->reviewRequest->id,
            'branch_name' => $branch->getTranslation('name', app()->getLocale()),
            'status' => $this->reviewRequest->status,
            'request_notes' => $this->reviewRequest->request_notes,
            'review_notes' => $this->reviewRequest->review_notes,
            'scheduled_review_date' => $this->reviewRequest->scheduled_review_date?->toISOString(),
            'reviewed_by' => $this->reviewRequest->reviewedBy?->name,
            'reviewed_at' => $this->reviewRequest->reviewed_at?->toISOString(),
            'requested_at' => $this->reviewRequest->requested_at->toISOString(),
        ];
    }
}
