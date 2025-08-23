<?php

namespace App\Services;

use App\Models\BranchScore;
use App\Models\BranchScoreHistory;
use App\Models\BranchScoreReviewRequest;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ScoreChangedNotification;
use App\Notifications\ReviewRequestStatusNotification;

class ScoreNotificationService
{
    /**
     * Send notification when a branch score changes
     */
    public function notifyScoreChange(BranchScoreHistory $scoreHistory): void
    {
        $branchScore = $scoreHistory->branchScore;
        $branch = $branchScore->branch;
        $siteSetting = $branch->siteSetting;
        
        // Get the gym owner
        $owner = $siteSetting->owner;
        
        if ($owner) {
            $owner->notify(new ScoreChangedNotification($scoreHistory));
        }
        
        // Also notify the branch manager if different from owner
        $manager = $branch->manager;
        if ($manager && $manager->id !== $owner?->id) {
            $manager->notify(new ScoreChangedNotification($scoreHistory));
        }
    }

    /**
     * Send notification when a review request status changes
     */
    public function notifyReviewRequestStatus(BranchScoreReviewRequest $reviewRequest): void
    {
        $requestedBy = $reviewRequest->requestedBy;
        
        if ($requestedBy) {
            $requestedBy->notify(new ReviewRequestStatusNotification($reviewRequest));
        }
    }

    /**
     * Send notification when a review request is created
     */
    public function notifyReviewRequestCreated(BranchScoreReviewRequest $reviewRequest): void
    {
        // This could notify master admins about new review requests
        $masterAdmins = User::whereHas('role', function($query) {
            $query->where('name', 'Master Admin');
        })->get();

        foreach ($masterAdmins as $admin) {
            // You can create a specific notification for this
            // $admin->notify(new NewReviewRequestNotification($reviewRequest));
        }
    }

    /**
     * Send notification for scheduled reviews
     */
    public function notifyScheduledReviews(): void
    {
        $upcomingReviews = BranchScore::where('next_review_date', '<=', now()->addDays(7))
            ->where('next_review_date', '>', now())
            ->with(['branch.siteSetting.owner', 'branch.manager'])
            ->get();

        foreach ($upcomingReviews as $branchScore) {
            $owner = $branchScore->branch->siteSetting->owner;
            $manager = $branchScore->branch->manager;
            
            if ($owner) {
                // $owner->notify(new UpcomingReviewNotification($branchScore));
            }
            
            if ($manager && $manager->id !== $owner?->id) {
                // $manager->notify(new UpcomingReviewNotification($branchScore));
            }
        }
    }

    /**
     * Send notification for overdue reviews
     */
    public function notifyOverdueReviews(): void
    {
        $overdueReviews = BranchScore::where('next_review_date', '<', now())
            ->where('is_active', true)
            ->with(['branch.siteSetting.owner', 'branch.manager'])
            ->get();

        foreach ($overdueReviews as $branchScore) {
            $owner = $branchScore->branch->siteSetting->owner;
            $manager = $branchScore->branch->manager;
            
            if ($owner) {
                // $owner->notify(new OverdueReviewNotification($branchScore));
            }
            
            if ($manager && $manager->id !== $owner?->id) {
                // $manager->notify(new OverdueReviewNotification($branchScore));
            }
        }
    }
}
