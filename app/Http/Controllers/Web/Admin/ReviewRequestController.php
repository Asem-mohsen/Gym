<?php

namespace App\Http\Controllers\Web\Admin;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\BranchScoreReviewRequest;
use App\Services\ReviewRequestService;
use App\Http\Requests\ReviewRequest\StoreReviewRequestRequest;
use App\Http\Requests\ReviewRequest\UpdateReviewRequestRequest;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;

class ReviewRequestController extends Controller
{
    protected $reviewRequestService;
    protected $siteSettingService;
    protected $siteSettingId;

    public function __construct(ReviewRequestService $reviewRequestService, SiteSettingService $siteSettingService)
    {
        $this->reviewRequestService = $reviewRequestService;
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        $data = $this->reviewRequestService->getReviewRequestsForGym($this->siteSettingId);
        return view('admin.review-requests.index', $data);
    }

    public function create(Request $request)
    {
        $data = $this->reviewRequestService->getCreateData($request, $this->siteSettingId);
        return view('admin.review-requests.create', $data);
    }

    public function store(StoreReviewRequestRequest $request)
    {
        try {
            $this->reviewRequestService->createReviewRequest($request->validated(), $this->siteSettingId);
            
            return redirect()->route('review-requests.index')->with('success', 'Review request submitted successfully. We will review it and get back to you.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to submit review request: ' . $e->getMessage());
        }
    }

    public function show(BranchScoreReviewRequest $reviewRequest)
    {
        $reviewRequest = $this->reviewRequestService->getReviewRequestForShow($reviewRequest, $this->siteSettingId);
        return view('admin.review-requests.show', compact('reviewRequest'));
    }

    public function edit(BranchScoreReviewRequest $reviewRequest)
    {
        try {
            $reviewRequest = $this->reviewRequestService->getReviewRequestForEdit($reviewRequest, $this->siteSettingId);
            return view('admin.review-requests.edit', compact('reviewRequest'));
        } catch (Exception $e) {
            return redirect()->route('review-requests.index')->with('error', 'Failed to edit review request: ' . $e->getMessage());
        }
    }

    public function update(UpdateReviewRequestRequest $request, BranchScoreReviewRequest $reviewRequest)
    {
        try {
            $this->reviewRequestService->updateReviewRequest($reviewRequest, $request->validated(), $this->siteSettingId);
            
            return redirect()->route('review-requests.show', $reviewRequest)
                ->with('success', 'Review request updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update review request: ' . $e->getMessage());
        }
    }

    public function destroy(BranchScoreReviewRequest $reviewRequest)
    {
        try {
            $this->reviewRequestService->deleteReviewRequest($reviewRequest, $this->siteSettingId);
            
            return redirect()->route('review-requests.index')
                ->with('success', 'Review request cancelled successfully.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel review request: ' . $e->getMessage());
        }
    }
}
