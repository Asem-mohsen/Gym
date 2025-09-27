<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BranchScore;
use App\Models\BranchScoreReviewRequest;
use App\Models\Document;
use App\Services\SiteSettingService;
use Illuminate\Support\Facades\Auth;

class ScoreDashboardController extends Controller
{
    protected $siteSettingService;
    protected $siteSettingId;

    public function __construct(SiteSettingService $siteSettingService)
    {
        $this->siteSettingService = $siteSettingService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        $siteSettingId = $this->siteSettingId;

        // Get branch scores for this gym
        $branchScores = BranchScore::whereHas('branch', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->with(['branch', 'branch.siteSetting'])->get();

        // Calculate statistics
        $averageScore = $branchScores->avg('score') ?? 0;
        $excellentBranches = $branchScores->where('score', '>=', 90)->count();
        $pendingReviews = BranchScoreReviewRequest::whereHas('branchScore.branch', function($query) use ($siteSettingId) {
            $query->where('site_setting_id', $siteSettingId);
        })->where('is_reviewed', false)->count();

        // Get available documents for this gym
        $documents = Document::whereHas('siteSettings', function($query) use ($siteSettingId) {
            $query->where('site_settings.id', $siteSettingId);
        })->orWhereDoesntHave('siteSettings')->where('is_active', true)->get();

        $availableDocuments = $documents->count();

        return view('admin.score-dashboard', compact(
            'branchScores',
            'averageScore',
            'excellentBranches',
            'pendingReviews',
            'documents',
            'availableDocuments'
        ));
    }

    public function show(BranchScore $branchScore)
    {
        if ($branchScore->branch->site_setting_id !== $this->siteSettingId) {
            abort(403, 'Unauthorized access to this branch score.');
        }

        $branchScore->load(['branch', 'branch.siteSetting', 'scoreItems.scoreCriteria', 'scoreHistory.changedBy']);

        return view('admin.branch-scores.show', compact('branchScore'));
    }

    public function create()
    {
        $branches = Branch::where('site_setting_id', $this->siteSettingId)
            ->whereDoesntHave('score')
            ->get();

        return view('admin.branch-scores.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'score' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify the branch belongs to this gym
        $branch = Branch::where('id', $request->branch_id)
            ->where('site_setting_id', $this->siteSettingId)
            ->firstOrFail();

        $branchScore = BranchScore::create([
            'branch_id' => $request->branch_id,
            'score' => $request->score,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        // Record initial score in history
        $branchScore->scoreHistory()->create([
            'old_score' => 0,
            'new_score' => $request->score,
            'change_amount' => $request->score,
            'changed_at' => now(),
            'change_reason' => 'Initial score assignment',
            'changed_by_id' => Auth::id(),
        ]);

        return redirect()->route('admin.score-dashboard')
            ->with('success', 'Branch score created successfully.');
    }

    public function edit(BranchScore $branchScore)
    {
        if ($branchScore->branch->site_setting_id !== $this->siteSettingId) {
            abort(403, 'Unauthorized access to this branch score.');
        }

        return view('admin.branch-scores.edit', compact('branchScore'));
    }

    public function update(Request $request, BranchScore $branchScore)
    {
        if ($branchScore->branch->site_setting_id !== $this->siteSettingId) {
            abort(403, 'Unauthorized access to this branch score.');
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'last_review_date' => 'nullable|date',
            'next_review_date' => 'nullable|date|after:today',
        ]);

        $oldScore = $branchScore->score;
        $newScore = $request->score;
        $changeAmount = $newScore - $oldScore;

        $branchScore->update([
            'score' => $newScore,
            'notes' => $request->notes,
            'last_review_date' => $request->last_review_date,
            'next_review_date' => $request->next_review_date,
        ]);

        // Record the change in history
        if ($changeAmount !== 0) {
            $branchScore->scoreHistory()->create([
                'old_score' => $oldScore,
                'new_score' => $newScore,
                'change_amount' => $changeAmount,
                'changed_at' => now(),
                'change_reason' => $request->change_reason ?? 'Score updated',
                'changed_by_id' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.score-dashboard')
            ->with('success', 'Branch score updated successfully.');
    }

    public function documents()
    {
        $siteSettingId = $this->siteSettingId;
        $documents = Document::whereHas('siteSettings', function($query) use ($siteSettingId) {
            $query->where('site_settings.id', $siteSettingId);
        })->orWhereDoesntHave('siteSettings')->where('is_active', true)->get();

        return view('admin.documents.index', compact('documents'));
    }

    public function downloadDocument(Document $document)
    {
        // Check if the document is available for this gym
        $isAvailable = $document->siteSettings()->where('site_settings.id', $this->siteSettingId)->exists() 
            || $document->siteSettings()->count() === 0;

        if (!$isAvailable) {
            abort(403, 'Document not available for this gym.');
        }

        $media = $document->getFirstMedia('document');
        
        if (!$media) {
            abort(404, 'Document file not found.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
