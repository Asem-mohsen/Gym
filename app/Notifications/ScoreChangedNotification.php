<?php

namespace App\Notifications;

use App\Models\BranchScoreHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Services\RealTimeNotificationService;

class ScoreChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $scoreHistory;
    protected $realTimeService;
    
    /**
     * Create a new notification instance.
     */
    public function __construct(BranchScoreHistory $scoreHistory)
    {
        $this->scoreHistory = $scoreHistory;
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
        $branchScore = $this->scoreHistory->branchScore;
        $branch = $branchScore->branch;
        $changeAmount = $this->scoreHistory->change_amount;
        $isIncrease = $changeAmount > 0;
        
        $title = 'Branch Score Updated - ' . $branch->getTranslation('name', app()->getLocale());
        $message = 'The score for branch "' . $branch->getTranslation('name', app()->getLocale()) . '" has been updated by ' . ($changeAmount > 0 ? '+' : '') . $changeAmount . ' points.';

        $notification = $this->realTimeService->createNotification(
            'score_changed',
            $title,
            $message,
            url('/admin/score-management/branch-scores/' . $branchScore->id),
            'View Branch Details',
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
        $branchScore = $this->scoreHistory->branchScore;
        $branch = $branchScore->branch;
        $changeAmount = $this->scoreHistory->change_amount;

        return [
            'type' => 'score_changed',
            'branch_score_id' => $branchScore->id,
            'branch_name' => $branch->getTranslation('name', app()->getLocale()),
            'old_score' => $this->scoreHistory->old_score,
            'new_score' => $this->scoreHistory->new_score,
            'change_amount' => $changeAmount,
            'change_reason' => $this->scoreHistory->change_reason,
            'changed_by' => $this->scoreHistory->changedBy?->name,
            'changed_at' => $this->scoreHistory->changed_at->toISOString(),
            'is_increase' => $changeAmount > 0,
        ];
    }
}
