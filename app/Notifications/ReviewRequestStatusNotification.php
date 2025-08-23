<?php

namespace App\Notifications;

use App\Models\BranchScoreReviewRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reviewRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(BranchScoreReviewRequest $reviewRequest)
    {
        $this->reviewRequest = $reviewRequest;
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
        $branchScore = $this->reviewRequest->branchScore;
        $branch = $branchScore->branch;
        $status = $this->reviewRequest->status;

        $mailMessage = (new MailMessage)
            ->subject('Review Request ' . ucfirst($status) . ' - ' . $branch->getTranslation('name', app()->getLocale()))
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($status === 'approved') {
            $mailMessage->line('Great news! Your review request for branch "' . $branch->getTranslation('name', app()->getLocale()) . '" has been **approved**.')
                ->line('**Scheduled Review Date:** ' . ($this->reviewRequest->scheduled_review_date ? $this->reviewRequest->scheduled_review_date->format('M d, Y H:i') : 'To be determined'))
                ->line('**Review Notes:** ' . ($this->reviewRequest->review_notes ?? 'No additional notes'))
                ->line('**Reviewed By:** ' . ($this->reviewRequest->reviewedBy?->name ?? 'System'))
                ->line('**Reviewed At:** ' . $this->reviewRequest->reviewed_at->format('M d, Y H:i'));
        } elseif ($status === 'rejected') {
            $mailMessage->line('Your review request for branch "' . $branch->getTranslation('name', app()->getLocale()) . '" has been **rejected**.')
                ->line('**Rejection Reason:** ' . ($this->reviewRequest->review_notes ?? 'No reason provided'))
                ->line('**Reviewed By:** ' . ($this->reviewRequest->reviewedBy?->name ?? 'System'))
                ->line('**Reviewed At:** ' . $this->reviewRequest->reviewed_at->format('M d, Y H:i'))
                ->line('You may submit a new review request with additional information or clarifications.');
        } else {
            $mailMessage->line('Your review request for branch "' . $branch->getTranslation('name', app()->getLocale()) . '" is still **pending** review.')
                ->line('We will review your request and get back to you soon.')
                ->line('**Request Notes:** ' . ($this->reviewRequest->request_notes ?? 'No notes provided'))
                ->line('**Requested At:** ' . $this->reviewRequest->requested_at->format('M d, Y H:i'));
        }

        return $mailMessage
            ->action('View Review Request', url('/admin/score-management/review-requests/' . $this->reviewRequest->id))
            ->line('Thank you for using our platform!');
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
