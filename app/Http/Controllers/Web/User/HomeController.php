<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\{ClassService, GalleryService, MembershipService, SiteSettingService, UserService};

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
    public function index(SiteSetting $siteSetting)
    {
        $memberships = $this->membershipService->getMemberships(siteSettingId: $siteSetting->id);
        $galleries = $this->galleryService->getGalleriesForModel(model: $siteSetting , limit: 6);
        $classes = $this->classService->getClasses(siteSettingId: $siteSetting->id);
        $trainers = $this->userService->getTrainers(siteSettingId: $siteSetting->id);

        return view('user.index', compact('memberships', 'galleries', 'classes', 'trainers'));
    }
}
