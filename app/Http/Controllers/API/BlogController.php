<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\{BlogPostResource, CategoryResource, TagResource};
use App\Models\{BlogPost, SiteSetting};
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(Request $request, SiteSetting $gym)
    {
        $filters = [
            'category' => $request->get('category'),
            'tag' => $request->get('tag'),
            'search' => $request->get('search'),
        ];
        
        $filters = array_filter($filters, function($value) {
            return !empty($value);
        });
        
        $blogPosts = $this->blogService->getBlogPosts($gym->id, true, perPage: 10, filters: $filters);
        $categories = $this->blogService->getCategories(withCount: ['blogPosts']);
        $tags = $this->blogService->getTags(withCount: ['blogPosts']);
        
        $data = [
            'blog_posts' => BlogPostResource::collection($blogPosts),
            'categories' => CategoryResource::collection($categories),
            'tags'       => TagResource::collection($tags),
            'filters' => [
                'applied' => $filters,
                'available' => [
                    'categories' => $categories->pluck('slug', 'name'),
                    'tags' => $tags->pluck('slug', 'name'),
                ]
            ]
        ];
        
        return successResponse($data, 'Blog posts retrieved successfully');
    }

    public function show(SiteSetting $gym, BlogPost $blogPost)
    {
        $blogPost = $this->blogService->showBlogPost(
            $blogPost->id, 
            [
                'media', 
                'categories', 
                'tags', 
                'user',
                'shares',
                'approvedComments.user',
                'approvedComments.likes',
                'approvedComments.children.user',
                'approvedComments.children.likes'
            ]
        );

        $data = [
            'blog_post' => new BlogPostResource($blogPost),
        ];

        return successResponse($data, 'Blog post retrieved successfully');
    }
}
