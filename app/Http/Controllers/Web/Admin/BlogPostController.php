<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogPosts\{AddBlogPostRequest , UpdateBlogPostRequest};
use App\Models\BlogPost;
use App\Services\{BlogService, SiteSettingService};
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BlogPostController extends Controller
{
    protected int $siteSettingId;
    public function __construct(protected BlogService $blogService, protected SiteSettingService $siteSettingService)
    {
        $this->blogService = $blogService;
        $this->siteSettingService = $siteSettingService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function index()
    {
        $blogPosts = $this->blogService->getBlogPosts($this->siteSettingId, false);
        return view('admin.blog-posts.index',compact('blogPosts'));
    }

    public function create()
    {
        $categories = $this->blogService->getCategories();
        $tags = $this->blogService->getTags();
        return view('admin.blog-posts.create', compact('categories', 'tags'));
    }

    public function store(AddBlogPostRequest $request)
    {
        try {
            $validated = $request->validated();
            $userId = Auth::user()->id;
            $this->blogService->createBlogPost($validated , $userId);
            return redirect()->route('blog-posts.index')->with('success', 'Blog post created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error happened while creating a new blog post, please try again in a few minutes.');
        }
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost)
    {
        try {
            $validated = $request->validated();
            $this->blogService->updateBlogPost($blogPost, $validated);
            return redirect()->route('blog-posts.index')->with('success', 'Blog post updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating blog post', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error happened while updating blog post, please try again in a few minutes.');
        }
    }
    

    public function edit(BlogPost $blogPost)
    {
        $categories = $this->blogService->getCategories();
        $tags = $this->blogService->getTags();
        return view('admin.blog-posts.edit',compact('blogPost', 'categories', 'tags'));
    }

    public function show(BlogPost $blogPost)
    {
        return view('admin.blog-posts.show', compact('blogPost'));
    }

    public function destroy(BlogPost $blogPost)
    {
        try {
            $this->blogService->deleteBlogPost($blogPost);
            return redirect()->route('blog-posts.index')->with('success', 'Blog post deleted successfully.');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Error happened while deleting blog post, please try again in a few minutes.');
        }
    }
}
