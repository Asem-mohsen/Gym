<?php

namespace App\Http\Controllers\API;

use App\Models\SiteSetting;
use App\Services\{ClassService, GalleryService, MembershipService, SiteSettingService, UserService, GymBrandingService, BranchService, ServiceService};
use App\Http\Controllers\Controller;
use App\Http\Resources\{MembershipResource, GalleryResource, ClassResource, TrainerResource, BranchResource, ServiceResource};

class HomeController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService,
        protected SiteSettingService $siteSettingService,
        protected GalleryService $galleryService,
        protected ClassService $classService,
        protected UserService $userService,
        protected GymBrandingService $gymBrandingService,
        protected BranchService $branchService,
        protected ServiceService $serviceService

    )
    {
        $this->membershipService = $membershipService;
        $this->galleryService = $galleryService;
        $this->classService = $classService;
        $this->userService = $userService;
        $this->gymBrandingService = $gymBrandingService;
        $this->branchService = $branchService;
        $this->serviceService = $serviceService;
    }

    public function index(SiteSetting $gym)
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $gym->id);
        $galleries = $this->galleryService->getGalleriesForPage($gym->id, 'home', 6);
        $classes = $this->classService->getClasses(siteSettingId: $gym->id);
        $trainers = $this->userService->getTrainers(siteSettingId: $gym->id);
        $branches = $this->branchService->getBranchesForPublic($gym->id);
        $services = $this->serviceService->getAvailableServices(siteSettingId: $gym->id);
        
        $data = [
            'memberships' => MembershipResource::collection($memberships),
            'trainers'    => TrainerResource::collection($trainers),
            'galleries'   => GalleryResource::collection($galleries),
            'classes'     => ClassResource::collection($classes),
            'branches'    => BranchResource::collection($branches),
            'services'    => ServiceResource::collection($services)
        ];

        return successResponse($data, 'Home data retrieved');
    }
}
