<?php

namespace App\Http\Controllers\Web\User;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\{ClassService, GalleryService, MembershipService, SiteSettingService, UserService, GymBrandingService, BranchService};

class NotFoundController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService,
        protected SiteSettingService $siteSettingService,
        protected GalleryService $galleryService,
        protected ClassService $classService,
        protected UserService $userService,
        protected GymBrandingService $gymBrandingService,
        protected BranchService $branchService
    )
    {
        $this->membershipService = $membershipService;
        $this->galleryService = $galleryService;
        $this->classService = $classService;
        $this->userService = $userService;
        $this->gymBrandingService = $gymBrandingService;
        $this->branchService = $branchService;
    }

    public function index(SiteSetting $siteSetting)
    {
        try {
            $memberships = $this->membershipService->getMemberships(siteSettingId: $siteSetting->id);
            $galleries = $this->galleryService->getGalleriesForPage($siteSetting->id, 'home', 6);
            $classes = $this->classService->getClasses(siteSettingId: $siteSetting->id);
            $trainers = $this->userService->getTrainers(siteSettingId: $siteSetting->id);
            $branches = $this->branchService->getBranchesForPublic($siteSetting->id);
            
            $brandingData = $this->gymBrandingService->getBrandingForAdmin($siteSetting->id);
            $branding = $brandingData['branding'] ?? [];

            return view('user.404', compact('memberships', 'galleries', 'classes', 'trainers', 'branches', 'branding'));
        } catch (Exception $e) {

            Log::warning('404 page error: ' . $e->getMessage());
            
            return view('user.404', [
                'trainers' => collect(),
                'siteSetting' => null
            ]);
        }
    }
}
