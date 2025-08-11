<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Services\{SiteSettingService, BlogService};

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(SiteSetting $gym)
    {
        $data = [
            'blogPosts' => $this->blogService->getBlogPosts($gym->id, true),
            'categories' => $this->blogService->getCategories(withCount: ['blogPosts']),
            'tags' => $this->blogService->getTags(withCount: ['blogPosts']),
        ];
        
        return successResponse($data, 'Blog posts retrieved successfully');
    }

    public function show(SiteSetting $gym, BlogPost $blogPost)
    {
        $data = [
            'blogPost' => $this->blogService->showBlogPost($blogPost->id, ['media', 'categories', 'tags']),
        ];

        return successResponse($data, 'Blog post retrieved successfully');
    }
}
