<?php

namespace App\Notifications;

use App\Models\BranchScoreHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScoreChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $scoreHistory;
    /**
     * Create a new notification instance.
     */
    public function __construct(BranchScoreHistory $scoreHistory)
    {
        $this->scoreHistory = $scoreHistory;
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
        $branchScore = $this->scoreHistory->branchScore;
        $branch = $branchScore->branch;
        $changeAmount = $this->scoreHistory->change_amount;
        $isIncrease = $changeAmount > 0;

        return (new MailMessage)
            ->subject('Branch Score Updated - ' . $branch->getTranslation('name', app()->getLocale()))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('The score for branch "' . $branch->getTranslation('name', app()->getLocale()) . '" has been updated.')
            ->line('**Previous Score:** ' . $this->scoreHistory->old_score)
            ->line('**New Score:** ' . $this->scoreHistory->new_score)
            ->line('**Change:** ' . ($isIncrease ? '+' : '') . $changeAmount . ' points')
            ->line('**Reason:** ' . ($this->scoreHistory->change_reason ?? 'No reason provided'))
            ->line('**Updated By:** ' . ($this->scoreHistory->changedBy?->name ?? 'System'))
            ->line('**Updated At:** ' . $this->scoreHistory->changed_at->format('M d, Y H:i'))
            ->action('View Branch Details', url('/admin/score-management/branch-scores/' . $branchScore->id))
            ->line('Thank you for using our platform!');
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
