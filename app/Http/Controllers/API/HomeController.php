<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\{ClassService, GalleryService, SiteSettingService , MembershipService};
use App\Services\UserService;

class HomeController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService,
        protected SiteSettingService $siteSettingService,
        protected GalleryService $galleryService,
        protected ClassService $classService,
        protected UserService $userService
    )
    {
        $this->membershipService = $membershipService;
        $this->galleryService = $galleryService;
        $this->classService = $classService;
        $this->userService = $userService;
    }

    public function index(SiteSetting $gym)
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $gym->id);
        $galleries = $this->galleryService->getGalleriesForModel(model: $gym, limit: 6);
        $classes = $this->classService->getClasses(siteSettingId: $gym->id);
        $trainers = $this->userService->getTrainers(siteSettingId: $gym->id);

        $data = [
            'memberships'=>$memberships,
            'trainers'   =>$trainers,
            'galleries'  =>$galleries,
            'classes'    =>$classes
        ];

        return successResponse($data, 'Home data retrieved');
    }
}
