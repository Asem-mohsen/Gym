<?php 
namespace App\Services;

use App\Models\Category;
use App\Models\Tag;
use App\Repositories\BlogRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BlogService
{
    public function __construct(protected BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getBlogPosts(int $siteSettingId, $isPublished = true)
    {
        return $this->blogRepository->getBlogPosts($siteSettingId, $isPublished);
    }

    public function getCategories(array $withCount = [])
    {
        return $this->blogRepository->getCategories($withCount);
    }

    public function getTags(array $withCount = [])
    {
        return $this->blogRepository->getTags($withCount);
    }

    public function createBlogPost(array $data, int $userId)
    {
        Log::info('createBlogPost called', ['data' => $data, 'userId' => $userId]);
        $categoryNames = $data['categories'] ?? [];
        $tagNames = $data['tags'] ?? [];
        unset($data['categories'], $data['tags']);

        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = now();
        $data['user_id'] = $userId;
        $data['excerpt'] = Str::limit(strip_tags($data['content']), 200);

        $image = $data['image'] ?? null;
        unset($data['image']);

        try {
            $blogPost = $this->blogRepository->createBlogPost($data);
            Log::info('Blog post created', ['blogPost' => $blogPost]);

            $categoryIds = $this->handleCategories($categoryNames);
            Log::info('Category IDs', ['categoryIds' => $categoryIds]);
            $tagIds = $this->handleTags($tagNames);
            Log::info('Tag IDs', ['tagIds' => $tagIds]);

            $blogPost->categories()->sync($categoryIds);
            $blogPost->tags()->sync($tagIds);

            if ($image) {
                $blogPost->addMedia($image)->toMediaCollection('blog_post_images');
                Log::info('Image added to media collection');
            }

            return $blogPost;
        } catch (\Exception $e) {
            Log::error('Error in createBlogPost', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function updateBlogPost($blogPost, array $newData)
    {
        return $this->blogRepository->updateBlogPost($blogPost,$newData);
    }

    public function showBlogPost($blogPost)
    {
        return $this->blogRepository->findById($blogPost->id);
    }

    public function deleteBlogPost($blogPost)
    {
        return $this->blogRepository->deleteBlogPost($blogPost);
    }

    protected function handleCategories(array $names)
    {
        $ids = [];
        foreach ($names as $name) {
            $slug = Str::slug($name);
            $category = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'slug' => $slug]
            );
            $ids[] = $category->id;
        }
        return $ids;
    }

    protected function handleTags(array $names)
    {
        $ids = [];
        foreach ($names as $name) {
            $slug = Str::slug($name);
            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'slug' => $slug]
            );
            $ids[] = $tag->id;
        }
        return $ids;
    }
}