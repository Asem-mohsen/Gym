<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Services\{SiteSettingService, BlogService};

class BlogController extends Controller
{
    protected $siteSettingId;
    protected $blogService;

    public function __construct(SiteSettingService $siteSettingService, BlogService $blogService)
    {
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
        $this->blogService = $blogService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $blogPosts = $this->blogService->getBlogPosts($this->siteSettingId, true);
        $categories = $this->blogService->getCategories(withCount: ['blogPosts']);
        $tags = $this->blogService->getTags(withCount: ['blogPosts']);
        
        return view('user.blog' , compact('blogPosts' , 'categories' , 'tags'));
    }

    public function show( SiteSetting $siteSetting, BlogPost $blogPost)
    {
        return view('user.blog-details' , compact('blogPost'));
    }
}
