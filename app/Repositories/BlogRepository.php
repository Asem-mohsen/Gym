<?php 
namespace App\Repositories;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class BlogRepository
{
    /**
     * Get all branches with their phones.
     */
    public function getBlogPosts(int $siteSettingId, $isPublished = true)
    {
        return BlogPost::with(['user.role', 'categories', 'tags'])
            ->whereHas('user.role', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->when($isPublished, function ($query) {
                $query->where('status', 'published');
            })
            ->get();
    }

    public function getCategories(array $withCount = [])
    {
        return Category::when(
            !empty($withCount),
            fn($query) => $query->withCount($withCount)
        )->get();
    }

    public function getTags(array $withCount = [])
    {
        return Tag::when(
            !empty($withCount),
            fn($query) => $query->withCount($withCount)
        )->get();
    }
    /**
     * Create a new branch with phones.
     */
    public function createBlogPost(array $blogPostData)
    {
        return DB::transaction(function () use ($blogPostData) {
            $blogPost = BlogPost::create($blogPostData);
            return $blogPost;
        });
    }

    /**
     * Update a branch and its phones.
     */
    public function updateBlogPost(BlogPost $blogPost, array $blogPostData)
    {
        return DB::transaction(function () use ($blogPost, $blogPostData) {

            $blogPost->update($blogPostData);
    
            return $blogPost;
        });
    }
    

    /**
     * Delete a branch and its phones.
     */
    public function deleteBlogPost(BlogPost $blogPost)
    {
        DB::transaction(function () use ($blogPost) {
            $blogPost->delete();
        });
    }

    /**
     * Find a branch by ID with its phones.
     */
    public function findById(int $id, array $with = [])
    {
        return BlogPost::with($with)->find($id);
    }

}