<?php 
namespace App\Services;

use Exception;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Repositories\BlogRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BlogService
{
    private const MEDIA_COLLECTION_MAIN_IMAGE = 'blog_post_images';
    private const MEDIA_COLLECTION_OTHER_IMAGES = 'blog_post_other_images';

    public function __construct(protected BlogRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getBlogPosts(int $siteSettingId, $isPublished = true, $perPage = null, $take = null, $orderBy = 'created_at', $orderByDirection = 'desc')
    {
        return $this->blogRepository->getBlogPosts(
            $siteSettingId,
            (bool) $isPublished,
            $perPage !== null ? (int) $perPage : null,
            $take !== null ? (int) $take : null,
            $orderBy,
            $orderByDirection
        );
    }

    public function getCategories(array $withCount = [])
    {
        return $this->blogRepository->getCategories($withCount);
    }

    public function getTags(array $withCount = [])
    {
        return $this->blogRepository->getTags($withCount);
    }

    public function createBlogPost(array $data, int $userId): BlogPost
    {
        [$categoryValues, $tagValues, $image, $images] = $this->extractAssociations($data);
        $this->applyDerivedFieldsForCreate($data, $userId);

        try {
            $blogPost = $this->blogRepository->createBlogPost($data);
            Log::info('Blog post created', ['blogPost' => $blogPost]);

            $this->syncCategoriesAndTags($blogPost, $categoryValues, $tagValues);
            $this->attachMediaOnCreate($blogPost, $image, $images);

            return $blogPost;
        } catch (Exception $e) {
            Log::error('Error in createBlogPost', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function updateBlogPost(BlogPost $blogPost, array $newData): BlogPost
    {
        [$categoryValues, $tagValues, $image, $images] = $this->extractAssociations($newData);
        $this->applyDerivedFieldsForUpdate($newData, $blogPost);

        try {
            $updatedBlogPost = $this->blogRepository->updateBlogPost($blogPost, $newData);
            Log::info('Blog post updated', ['blogPost' => $updatedBlogPost]);

            $this->syncCategoriesAndTags($updatedBlogPost, $categoryValues, $tagValues);
            $this->updateMediaOnUpdate($updatedBlogPost, $image, $images);

            return $updatedBlogPost;
        } catch (Exception $e) {
            Log::error('Error in updateBlogPost', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'blogPostId' => $blogPost->id,
                'newData' => $newData
            ]);
            throw $e;
        }
    }

    public function showBlogPost($blogPostId, array $with = [])
    {
        return $this->blogRepository->findById($blogPostId, $with);
    }

    public function deleteBlogPost($blogPost)
    {
        return $this->blogRepository->deleteBlogPost($blogPost);
    }

    protected function handleCategories(array $values)
    {
        $ids = [];
        foreach ($values as $value) {
            if (is_numeric($value)) {
                $ids[] = (int) $value;
            } else {
                $slug = Str::slug($value);
                $category = Category::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $value, 'slug' => $slug]
                );
                $ids[] = $category->id;
            }
        }
        return $ids;
    }

    protected function handleTags(array $values)
    {
        $ids = [];
        foreach ($values as $value) {
            if (is_numeric($value)) {
                $ids[] = (int) $value;
            } else {
                $slug = Str::slug($value);
                $tag = Tag::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $value, 'slug' => $slug]
                );
                $ids[] = $tag->id;
            }
        }
        return $ids;
    }

    /**
     * Extract relations and media fields from input and unset them from the data array.
     *
     * @return array{0: array, 1: array, 2: mixed, 3: array}
     */
    private function extractAssociations(array & $data): array
    {
        $categoryValues = $data['categories'] ?? [];
        $tagValues = $data['tags'] ?? [];
        unset($data['categories'], $data['tags']);

        $image = $data['image'] ?? null;
        unset($data['image']);

        $images = $data['images'] ?? [];
        unset($data['images']);

        return [$categoryValues, $tagValues, $image, $images];
    }

    private function applyDerivedFieldsForCreate(array & $data, int $userId): void
    {
        $data['slug'] = Str::slug($data['title']);
        $data['published_at'] = now();
        $data['user_id'] = $userId;
        $data['excerpt'] = Str::limit(strip_tags($data['content']), 200);
    }

    private function applyDerivedFieldsForUpdate(array & $data, BlogPost $blogPost): void
    {
        if (isset($data['title']) && $data['title'] !== $blogPost->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (isset($data['content']) && $data['content'] !== $blogPost->content) {
            $data['excerpt'] = Str::limit(strip_tags($data['content']), 200);
        }

        if (isset($data['published_at']) && ($data['status'] ?? null) === 'published') {
            if (empty($data['published_at'])) {
                $data['published_at'] = now();
            }
        } elseif (($data['status'] ?? null) === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = now();
        }
    }

    private function syncCategoriesAndTags(BlogPost $blogPost, array $categoryValues, array $tagValues): void
    {
        if (empty($categoryValues)) {
            $blogPost->categories()->detach();
        } else {
            $categoryIds = $this->handleCategories($categoryValues);
            $blogPost->categories()->sync($categoryIds);
        }

        if (empty($tagValues)) {
            $blogPost->tags()->detach();
        } else {
            $tagIds = $this->handleTags($tagValues);
            $blogPost->tags()->sync($tagIds);
        }
    }

    private function attachMediaOnCreate(BlogPost $blogPost, $image, array $images): void
    {
        if ($image) {
            $blogPost->addMedia($image)->toMediaCollection(self::MEDIA_COLLECTION_MAIN_IMAGE);
        }

        foreach ($images as $img) {
            $blogPost->addMedia($img)->toMediaCollection(self::MEDIA_COLLECTION_OTHER_IMAGES);
        }
    }

    private function updateMediaOnUpdate(BlogPost $blogPost, $image, array $images): void
    {
        if ($image) {
            $blogPost->clearMediaCollection(self::MEDIA_COLLECTION_MAIN_IMAGE);
            $blogPost->addMedia($image)->toMediaCollection(self::MEDIA_COLLECTION_MAIN_IMAGE);
        }

        if (!empty($images)) {
            $blogPost->clearMediaCollection(self::MEDIA_COLLECTION_OTHER_IMAGES);
            foreach ($images as $img) {
                $blogPost->addMedia($img)->toMediaCollection(self::MEDIA_COLLECTION_OTHER_IMAGES);
            }
        }
    }
}