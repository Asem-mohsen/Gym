<?php 
namespace App\Repositories;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class BlogRepository
{
    /**
     * Get all blog posts with optional filtering.
     */
    public function getBlogPosts(int $siteSettingId, $isPublished = true, ?int $perPage = null, ?int $take = null, $orderBy = 'created_at', $orderByDirection = 'desc', array $filters = [])
    {
        $query = BlogPost::with(['user.gyms', 'categories', 'tags'])
            ->whereHas('user.gyms', function ($query) use ($siteSettingId) {
                $query->where('site_setting_id', $siteSettingId);
            })
            ->when($isPublished, function ($query) {
                $query->where('status', 'published');
            })
            ->when($take, function ($query) use ($take) {
                $query->take($take);
            })
            ->when($orderBy, function ($query) use ($orderBy, $orderByDirection) {
                $query->orderBy($orderBy, $orderByDirection);
            })
            ->when(!empty($filters['category']), function ($query) use ($filters) {
                $query->whereHas('categories', function ($q) use ($filters) {
                    $q->where('slug', $filters['category']);
                });
            })
            ->when(!empty($filters['tag']), function ($query) use ($filters) {
                $query->whereHas('tags', function ($q) use ($filters) {
                    $q->where('slug', $filters['tag']);
                });
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('content', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('excerpt', 'like', '%' . $filters['search'] . '%');
                });
            });
            
        return $perPage 
            ? $query->paginate($perPage)
            : $query->get();
            
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