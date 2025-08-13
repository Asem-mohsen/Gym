<?php

namespace App\Repositories;

use App\Models\BlogPostShare;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BlogPostShareRepository
{
    /**
     * Create a new share record
     */
    public function createShare(array $data): BlogPostShare
    {
        return DB::transaction(function () use ($data) {
            return BlogPostShare::create($data);
        });
    }

    /**
     * Get share count for a specific platform
     */
    public function getShareCountByPlatform(int $blogPostId, string $platform): int
    {
        return BlogPostShare::where('blog_post_id', $blogPostId)
            ->byPlatform($platform)
            ->count();
    }

    /**
     * Get all shares for a blog post
     */
    public function getSharesForBlogPost(int $blogPostId): Collection
    {
        return BlogPostShare::where('blog_post_id', $blogPostId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user has already shared on a specific platform
     */
    public function hasUserSharedOnPlatform(int $blogPostId, int $userId, string $platform): bool
    {
        return BlogPostShare::where('blog_post_id', $blogPostId)
            ->where('user_id', $userId)
            ->byPlatform($platform)
            ->exists();
    }

    /**
     * Get share statistics for a blog post
     */
    public function getShareStatistics(int $blogPostId): array
    {
        return [
            'facebook' => $this->getShareCountByPlatform($blogPostId, 'facebook'),
            'twitter' => $this->getShareCountByPlatform($blogPostId, 'twitter'),
            'email' => $this->getShareCountByPlatform($blogPostId, 'email'),
            'total' => BlogPostShare::where('blog_post_id', $blogPostId)->count(),
        ];
    }

    /**
     * Get total shares across all platforms
     */
    public function getTotalShares(int $blogPostId): int
    {
        return BlogPostShare::where('blog_post_id', $blogPostId)->count();
    }
}
