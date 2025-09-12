<?php

namespace App\Http\Controllers\Web\Admin;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deactivation\DeactivationRequest;
use App\Models\{SiteSetting, Branch, Document, User};
use App\Services\Deletations\GymDeactivationService;
use App\Services\SiteSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{DB, Auth, Log};

class GymDeactivationController extends Controller
{
    protected $siteSettingsId;

    public function __construct(protected GymDeactivationService $deactivationService, protected SiteSettingService $siteSettingService)
    {
        $this->deactivationService = $deactivationService;
        $this->siteSettingsId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Show the deactivation management page
     */
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        
        if (!$user->hasRole('admin')) return redirect()->route('admin.dashboard')->with('error', 'Access denied. Admin role required.');
        
        $gym = $user->site;
        
        $gyms = collect([$gym]);
        
        $branches = $gym->branches;
        
        $policyDocuments = Document::where('document_type', 'policies')
            ->where('is_active', true)
            ->get();
        
        return view('admin.deactivation.index', compact('gyms', 'branches', 'gym', 'policyDocuments'));
    }

    /**
     * Deactivate a branch
     */
    public function deactivateBranch(DeactivationRequest $request, Branch $branch): JsonResponse
    {
        try {
            if ($this->siteSettingsId !== $branch->site_setting_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. You can only deactivate branches in your own gym.'
                ], 403);
            }

            DB::beginTransaction();

            $this->deactivationService->deactivateBranch($branch);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Branch has been successfully deactivated. All associated data has been soft deleted.'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deactivating the branch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate a gym
     */
    public function deactivateGym(DeactivationRequest $request, SiteSetting $siteSetting): JsonResponse
    {
        try {
            if ($this->siteSettingsId !== $siteSetting->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. You can only deactivate your own gym.'
                ], 403);
            }

            DB::beginTransaction();

            $this->deactivationService->deactivateGym($siteSetting);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gym has been successfully deactivated. Data export has been sent to the gym owner. All accounts will be deactivated in 2 days and data will be permanently deleted in 30 days.'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deactivating the gym: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gym data for preview before deactivation
     */
    public function getGymDataPreview(): JsonResponse
    {
        try {
            $siteSetting = $this->siteSettingService->getSiteSettingById($this->siteSettingsId);

            $data = $this->deactivationService->getGymDataForExport($siteSetting);

            // Return summary statistics instead of full data
            $summary = [
                'gym_name' => $siteSetting->gym_name,
                'owner_email' => $siteSetting->owner?->email,
                'total_users' => count($data['users']),
                'total_branches' => count($data['branches']),
                'total_services' => count($data['services']),
                'total_classes' => count($data['classes']),
                'total_memberships' => count($data['memberships']),
                'total_offers' => count($data['offers']),
                'total_payments' => count($data['payments']),
                'total_invitations' => count($data['invitations']),
                'total_blog_posts' => count($data['blog_posts']),
                'total_comments' => count($data['comments']),
                // 'total_galleries' => count($data['galleries']),
                // 'total_bookings' => count($data['bookings']),
                'total_contacts' => count($data['contacts']),
                // 'total_lockers' => count($data['lockers']),
                // 'total_coaching_sessions' => count($data['coaching_sessions']),
                // 'total_transactions' => count($data['transactions']),
                'total_trainer_information' => count($data['trainer_information']),
                'total_documents' => count($data['documents']),
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving gym data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get branch data for preview before deactivation
     */
    public function getBranchDataPreview(Branch $branch): JsonResponse
    {
        try {
            $summary = [
                'branch_name' => $branch->name,
                'branch_location' => $branch->location,
                'manager_name' => $branch->manager?->name,
                'manager_email' => $branch->manager?->email,
                'branch_score' => $branch->score()->first()?->score ?? 0,
                'total_users' => $branch->subscriptions()->count(),
                'total_trainers' => $branch->trainers()->count(),
                'total_subscribers' => $branch->subscriptions()->count(),
                'total_services' => $branch->services()->count(),
                'total_classes' => $branch->classes()->count(), // to do : change to classes
                'total_machines' => $branch->machines()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving branch data: ' . $e->getMessage()
            ], 500);
        }
    }
}
