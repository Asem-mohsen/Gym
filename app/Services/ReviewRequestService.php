<?php

namespace App\Services;

use App\Repositories\ReviewRequestRepository;
use App\Models\BranchScoreReviewRequest;
use App\Models\Branch;
use App\Models\BranchScore;
use App\Repositories\BranchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewRequestService
{
    public function __construct(
        protected ReviewRequestRepository $reviewRequestRepository,
        protected BranchRepository $branchRepository,
    ) {
        $this->reviewRequestRepository = $reviewRequestRepository;
        $this->branchRepository = $branchRepository;
    }

    public function getReviewRequestsForGym(int $siteSettingId): array
    {
        $reviewRequests = $this->reviewRequestRepository->getReviewRequestsForGym($siteSettingId);
        $statistics = $this->reviewRequestRepository->getReviewRequestStatistics($siteSettingId);

        return [
            'reviewRequests' => $reviewRequests,
            'totalRequests' => $statistics['totalRequests'],
            'pendingRequests' => $statistics['pendingRequests'],
            'approvedRequests' => $statistics['approvedRequests'],
            'rejectedRequests' => $statistics['rejectedRequests'],
        ];
    }

    public function getCreateData(Request $request, int $siteSettingId): array
    {
        $branches = $this->branchRepository->getBranches(siteSettingId: $siteSettingId , with:['siteSetting']);

        $branchScores = $this->reviewRequestRepository->getBranchScoresForGym($siteSettingId);

        $branches->each(function ($branch) use ($branchScores) {
            $branch->branch_score = $branchScores->get($branch->id);
        });
        
        $selectedBranchScoreId = $request->get('branch_score_id');

        return [
            'branches' => $branches,
            'selectedBranchScoreId' => $selectedBranchScoreId,
        ];
    }

    public function createReviewRequest(array $data, int $siteSettingId): BranchScoreReviewRequest
    {
        // Check if the branch_score_id is actually a branch_id (for branches without scores)
        $branch = Branch::where('site_setting_id', $siteSettingId)->find($data['branch_score_id']);
        
        if ($branch) {
            // This is a branch without a score, we need to create a branch score first
            $branchScore = BranchScore::create([
                'branch_id' => $branch->id,
                'score' => 0, // Default score
                'is_active' => true,
            ]);
        } else {
            // This is an existing branch score
            $branchScore = $this->reviewRequestRepository->getBranchScoreById($data['branch_score_id'], $siteSettingId);
            
            if (!$branchScore) {
                throw new \Exception('Branch score not found or not accessible.');
            }
        }

        $reviewRequestData = [
            'branch_score_id' => $branchScore->id,
            'requested_by_id' => Auth::id(),
            'requested_at' => now(),
            'request_notes' => $data['request_notes'],
            'scheduled_review_date' => $data['scheduled_review_date'] ?? null,
            'is_reviewed' => false,
            'is_approved' => false,
        ];

        $reviewRequest = $this->reviewRequestRepository->createReviewRequest($reviewRequestData);

        if (isset($data['supporting_documents']) && is_array($data['supporting_documents'])) {
            foreach ($data['supporting_documents'] as $document) {
                $reviewRequest->addMedia($document)->toMediaCollection('supporting_documents');
            }
        }

        return $reviewRequest;
    }

    public function getReviewRequestForShow(BranchScoreReviewRequest $reviewRequest, int $siteSettingId): BranchScoreReviewRequest
    {
        if (!$this->reviewRequestRepository->isReviewRequestAccessible($reviewRequest, $siteSettingId)) {
            throw new \Exception('Unauthorized access to this review request.');
        }

        return $reviewRequest;
    }

    public function getReviewRequestForEdit(BranchScoreReviewRequest $reviewRequest, int $siteSettingId): BranchScoreReviewRequest
    {
        $reviewRequest = $this->getReviewRequestForShow($reviewRequest, $siteSettingId);

        if (!$this->reviewRequestRepository->isReviewRequestEditable($reviewRequest)) {
            throw new \Exception('Cannot edit a approved request (only pending requests can be edited).');
        }

        return $reviewRequest;
    }

    public function updateReviewRequest(BranchScoreReviewRequest $reviewRequest, array $data, int $siteSettingId): bool
    {
        if (!$this->reviewRequestRepository->isReviewRequestAccessible($reviewRequest, $siteSettingId)) {
            throw new \Exception('Unauthorized access to this review request.');
        }

        if (!$this->reviewRequestRepository->isReviewRequestEditable($reviewRequest)) {
            throw new \Exception('Cannot update a reviewed request.');
        }

        $updateData = [
            'request_notes' => $data['request_notes'],
            'scheduled_review_date' => $data['scheduled_review_date'] ?? null,
        ];

        $updated = $this->reviewRequestRepository->updateReviewRequest($reviewRequest, $updateData);

        // Handle additional document uploads
        if (isset($data['supporting_documents']) && is_array($data['supporting_documents'])) {
            foreach ($data['supporting_documents'] as $document) {
                $reviewRequest->addMedia($document)->toMediaCollection('supporting_documents');
            }
        }

        return $updated;
    }

    public function deleteReviewRequest(BranchScoreReviewRequest $reviewRequest, int $siteSettingId): bool
    {
        $reviewRequest = $this->getReviewRequestForShow($reviewRequest, $siteSettingId);

        if (!$this->reviewRequestRepository->isReviewRequestEditable($reviewRequest)) {
            throw new \Exception('Cannot delete a reviewed request.');
        }

        return $this->reviewRequestRepository->deleteReviewRequest($reviewRequest);
    }

    public function validateBranchScoreAccess(int $branchScoreId, int $siteSettingId): bool
    {
        $branchScore = $this->reviewRequestRepository->getBranchScoreById($branchScoreId, $siteSettingId);
        
        return $branchScore !== null;
    }
}
