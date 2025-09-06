<?php

namespace App\Services\Deletations;

use App\Models\{SiteSetting, User, Branch, ClassModel, Invitation, BlogPost, Comment, Membership, Payment, Booking, Gallery, Feature, Category, Tag, Contact, Locker, CoachingSession, Transaction, TrainerInformation, Phone, ClassSchedule, ClassPricing, BlogPostShare, Document, ScoreCriteria, BranchScore, BranchScoreItem, BranchScoreHistory, BranchScoreReviewRequest};
use App\Mail\GymDeactivationDataExport;
use Illuminate\Support\Facades\{Mail, Storage, DB, Log};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GymDataExport;
use App\Jobs\SendGymDeactivationEmailsJob;

class GymDeactivationService
{
    /**
     * Deactivate a branch and all its associated data
     */
    public function deactivateBranch(Branch $branch): void
    {
        DB::transaction(function () use ($branch) {
            // Soft delete the branch
            $branch->delete();

            // Deactivate all users associated with this branch
            $users = User::whereHas('subscriptions', function ($query) use ($branch) {
                $query->where('branch_id', $branch->id);
            })->get();

            foreach ($users as $user) {
                $user->update(['status' => 'inactive']);
            }

            // Soft delete services associated with this branch
            $branch->services()->delete();

            // Soft delete classes associated with this branch
            ClassModel::where('site_setting_id', $branch->site_setting_id)->delete();

            // Soft delete invitations sent by users in this branch
            Invitation::whereHas('inviter', function ($query) use ($branch) {
                $query->whereHas('subscriptions', function ($subQuery) use ($branch) {
                    $subQuery->where('branch_id', $branch->id);
                });
            })->delete();

            // Soft delete all related data
            $branch->phones()->delete();
            $branch->galleries()->delete();
            $branch->machines()->detach();
            $branch->subscriptions()->delete();
            $branch->payments()->delete();
        });
    }

    /**
     * Deactivate a gym and prepare for data export
     */
    public function deactivateGym(SiteSetting $gym): void
    {
        DB::transaction(function () use ($gym) {
            // Send deactivation notification emails to regular users before deletion
            $this->sendDeactivationNotifications($gym);

            // Soft delete the gym
            $gym->delete();

            // Soft delete all branches
            $gym->branches()->delete();

            // Soft delete all users
            $gym->users()->delete();

            // Schedule data export email
            $this->scheduleDataExport($gym);
        });
    }

    /**
     * Schedule data export email to gym owner
     */
    private function scheduleDataExport(SiteSetting $gym): void
    {
        $this->sendDataExportEmail($gym);

        dispatch(function () use ($gym) {
            $this->deactivateAllAccounts($gym);
        })->delay(now()->addDays(2));

        dispatch(function () use ($gym) {
            $this->permanentlyDeleteGym($gym);
        })->delay(now()->addMonth());
    }

    /**
     * Send data export email to gym owner
     */
    private function sendDataExportEmail(SiteSetting $gym): void
    {
        $gymOwner = $gym->owner;
        
        if (!$gymOwner || !$gymOwner->email) {
            return;
        }

        $excelFile = $this->generateGymDataExcel($gym);

        // Send email with attachment
        Mail::to($gymOwner->email)->send(new GymDeactivationDataExport($gym, $excelFile));

        // Clean up temporary file
        Storage::delete($excelFile);
    }

    /**
     * Generate Excel file with all gym data
     */
    private function generateGymDataExcel(SiteSetting $gym): string
    {
        $fileName = 'gym_data_' . $gym->id . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $filePath = 'temp/' . $fileName;

        Excel::store(new GymDataExport($gym), $filePath);

        return $filePath;
    }

    /**
     * Deactivate all accounts after 2 days
     */
    private function deactivateAllAccounts(SiteSetting $gym): void
    {
        DB::transaction(function () use ($gym) {
            // Soft delete all users
            $gym->users()->delete();

            // Soft delete all branches
            $gym->branches()->delete();

            // Soft delete all services
            $gym->services()->delete();

            // Soft delete all classes
            $gym->classes()->delete();

            // Soft delete all memberships
            $gym->memberships()->delete();

            // Soft delete all offers
            $gym->offers()->delete();

            // Soft delete all payments
            $gym->payments()->delete();

            // Soft delete all galleries
            $gym->galleries()->delete();

            // Soft delete all roles
            $gym->roles()->delete();

            // Soft delete all invitations
            Invitation::where('site_setting_id', $gym->id)->delete();

            // Soft delete all blog posts and related data
            $blogPosts = BlogPost::where('user_id', $gym->owner_id)->get();
            foreach ($blogPosts as $blogPost) {
                $blogPost->comments()->delete();
                $blogPost->shares()->delete();
                $blogPost->delete();
            }

            // Soft delete all bookings
            Booking::where('site_setting_id', $gym->id)->delete();

            // Soft delete all contacts
            Contact::where('site_setting_id', $gym->id)->delete();

            // Soft delete all lockers
            Locker::where('site_setting_id', $gym->id)->delete();

            // Soft delete all coaching sessions
            CoachingSession::where('site_setting_id', $gym->id)->delete();

            // Soft delete all transactions
            Transaction::where('site_setting_id', $gym->id)->delete();

            // Soft delete all trainer information
            TrainerInformation::whereHas('user', function ($query) use ($gym) {
                $query->whereHas('gyms', function ($subQuery) use ($gym) {
                    $subQuery->where('site_setting_id', $gym->id);
                });
            })->delete();

            // Soft delete all phones
            Phone::whereHas('branch', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();

            // Soft delete all class schedules and pricings
            ClassSchedule::whereHas('class', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();

            ClassPricing::whereHas('class', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();

            // Soft delete all blog post shares
            BlogPostShare::whereHas('blogPost', function ($query) use ($gym) {
                $query->where('user_id', $gym->owner_id);
            })->delete();

            // Soft delete all comment likes
            DB::table('comment_likes')->whereIn('comment_id', function ($query) use ($gym) {
                $query->select('id')
                    ->from('comments')
                    ->whereIn('blog_post_id', function ($subQuery) use ($gym) {
                        $subQuery->select('id')
                            ->from('blog_posts')
                            ->where('user_id', $gym->owner_id);
                    });
            })->delete();

            // Soft delete all documents
            $gym->documents()->delete();

            // Soft delete all score-related data
            ScoreCriteria::where('site_setting_id', $gym->id)->delete();
            BranchScore::whereHas('branch', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();
            BranchScoreItem::whereHas('branchScore.branch', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();
            BranchScoreHistory::whereHas('branchScore.branch', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();
            BranchScoreReviewRequest::whereHas('branchScore.branch', function ($query) use ($gym) {
                $query->where('site_setting_id', $gym->id);
            })->delete();

            // Soft delete the gym itself
            $gym->delete();
        });
    }

    /**
     * Permanently delete gym and all data after 1 month
     */
    private function permanentlyDeleteGym(SiteSetting $gym): void
    {
        DB::transaction(function () use ($gym) {
            // Permanently delete all related data
            $gym->users()->forceDelete();
            $gym->branches()->forceDelete();
            $gym->services()->forceDelete();
            $gym->classes()->forceDelete();
            $gym->memberships()->forceDelete();
            $gym->offers()->forceDelete();
            $gym->payments()->forceDelete();
            $gym->galleries()->forceDelete();
            $gym->roles()->forceDelete();
            $gym->documents()->detach();

            // Permanently delete all other related data
            Invitation::where('site_setting_id', $gym->id)->forceDelete();
            Booking::where('site_setting_id', $gym->id)->forceDelete();
            Contact::where('site_setting_id', $gym->id)->forceDelete();
            Locker::where('site_setting_id', $gym->id)->forceDelete();
            CoachingSession::where('site_setting_id', $gym->id)->forceDelete();
            Transaction::where('site_setting_id', $gym->id)->forceDelete();

            // Permanently delete the gym
            $gym->forceDelete();
        });
    }

    /**
     * Get all gym data for export
     */
    public function getGymDataForExport(SiteSetting $gym): array
    {
        return [
            'gym_info' => $gym->toArray(),
            'users' => $gym->users()->whereHas('roles', function ($query) {
                $query->where('name', 'regular_user');
            })->get()->toArray(),
            'branches' => $gym->branches()->with('phones', 'manager')->get()->toArray(),
            'services' => $gym->services()->get()->toArray(),
            'classes' => $gym->classes()->with('trainers', 'schedules', 'pricings')->get()->toArray(),
            'memberships' => $gym->memberships()->with('features')->get()->toArray(),
            'offers' => $gym->offers()->get()->toArray(),
            'payments' => $gym->payments()->with('user')->get()->toArray(),
            'invitations' => Invitation::where('site_setting_id', $gym->id)->with('inviter', 'usedBy', 'membership')->get()->toArray(),
            'blog_posts' => BlogPost::where('user_id', $gym->owner_id)->with('categories', 'tags', 'comments', 'shares')->get()->toArray(),
            'comments' => Comment::whereHas('blogPost', function ($query) use ($gym) {
                $query->where('user_id', $gym->owner_id);
            })->with('user', 'likes')->get()->toArray(),
            'galleries' => $gym->galleries()->get()->toArray(),
            // 'bookings' => Booking::where('site_setting_id', $gym->id)->with('user')->get()->toArray(),
            'contacts' => Contact::where('site_setting_id', $gym->id)->get()->toArray(),
            // 'lockers' => Locker::where('site_setting_id', $gym->id)->get()->toArray(),
            // 'coaching_sessions' => CoachingSession::where('site_setting_id', $gym->id)->with('user')->get()->toArray(),
            // 'transactions' => Transaction::where('site_setting_id', $gym->id)->get()->toArray(),
            'trainer_information' => TrainerInformation::whereHas('user', function ($query) use ($gym) {
                $query->whereHas('gyms', function ($subQuery) use ($gym) {
                    $subQuery->where('site_setting_id', $gym->id);
                });
            })->with('user')->get()->toArray(),
            'documents' => $gym->documents()->get()->toArray(),
            'score_data' => [
                // 'criteria' => ScoreCriteria::where('site_setting_id', $gym->id)->get()->toArray(),
                'branch_scores' => BranchScore::whereHas('branch', function ($query) use ($gym) {
                    $query->where('site_setting_id', $gym->id);
                })->with('branch')->get()->toArray(),
                'review_requests' => BranchScoreReviewRequest::whereHas('branchScore.branch', function ($query) use ($gym) {
                    $query->where('site_setting_id', $gym->id);
                })->with('branchScore.branch')->get()->toArray(),
            ],
        ];
    }

    public function sendDeactivationNotifications(SiteSetting $gym): void
    {
        try {
            SendGymDeactivationEmailsJob::dispatch($gym);

        } catch (\Exception $e) {
            Log::error('Failed to dispatch gym deactivation email job', [
                'gym_id' => $gym->id,
                'error' => $e->getMessage()
            ]);
        }
    }

}
