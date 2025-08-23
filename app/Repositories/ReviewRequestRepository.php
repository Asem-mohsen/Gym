<?php

namespace App\Repositories;

use App\Models\BranchScoreReviewRequest;
use App\Models\BranchScore;
use Illuminate\Database\Eloquent\Collection;

class ReviewRequestRepository
{
    protected $model;

    public function __construct(BranchScoreReviewRequest $model)
    {
        $this->model = $model;
    }

    public function getReviewRequestsForGym(int $siteSettingId): Collection
    {
        return $this->model->whereHas('branchScore.branch', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->with(['branchScore.branch', 'branchScore.branch.siteSetting', 'requestedBy', 'reviewedBy'])
        ->orderBy('requested_at', 'desc')
        ->get();
    }

    public function getBranchScoresForGym(int $siteSettingId): Collection
    {
        return BranchScore::whereHas('branch', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->with(['branch'])->get()->keyBy('branch_id');
    }

    public function getBranchScoreById(int $id, int $siteSettingId): ?BranchScore
    {
        return BranchScore::whereHas('branch', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->find($id);
    }

    public function createReviewRequest(array $data): BranchScoreReviewRequest
    {
        return $this->model->create($data);
    }

    public function updateReviewRequest(BranchScoreReviewRequest $reviewRequest, array $data): bool
    {
        return $reviewRequest->update($data);
    }

    public function deleteReviewRequest(BranchScoreReviewRequest $reviewRequest): bool
    {
        return $reviewRequest->delete();
    }

    public function isReviewRequestAccessible(BranchScoreReviewRequest $reviewRequest, int $siteSettingId): bool
    {
        return $reviewRequest->branchScore->branch->site_setting_id === $siteSettingId;
    }

    public function isReviewRequestEditable(BranchScoreReviewRequest $reviewRequest): bool
    {
        return !$reviewRequest->is_reviewed;
    }

    public function getReviewRequestStatistics(int $siteSettingId): array
    {
        $query = $this->model->whereHas('branchScore.branch', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        });

        return [
            'totalRequests' => $query->count(),
            'pendingRequests' => $query->where('is_reviewed', false)->count(),
            'approvedRequests' => $query->where('is_reviewed', true)->where('is_approved', true)->count(),
            'rejectedRequests' => $query->where('is_reviewed', true)->where('is_approved', false)->count(),
        ];
    }
}
