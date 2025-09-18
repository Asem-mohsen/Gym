<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\BlogPostShare;
use App\Models\User;
use App\Repositories\BlogPostShareRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class BlogPostShareService
{
    protected $shareRepository;

    public function __construct(BlogPostShareRepository $shareRepository)
    {
        $this->shareRepository = $shareRepository;
    }

    /**
     * Record a social media share
     */
    public function recordShare(int $blogPostId, string $platform, Request $request, int $userId): BlogPostShare
    {
        try {
            
            $shareData = [
                'blog_post_id' => $blogPostId,
                'user_id' => $userId,
                'platform' => $platform,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            $share = $this->shareRepository->createShare($shareData);

            Log::info('Blog post share recorded', [
                'blog_post_id' => $blogPostId,
                'platform' => $platform,
                'user_id' => $userId,
                'share_id' => $share->id
            ]);

            return $share;
        } catch (Exception $e) {
            Log::error('Error recording blog post share', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPostId,
                'platform' => $platform
            ]);
            throw $e;
        }
    }

    /**
     * Get share statistics for a blog post
     */
    public function getShareStatistics(int $blogPostId): array
    {
        return $this->shareRepository->getShareStatistics($blogPostId);
    }

    /**
     * Check if user has already shared on a specific platform
     */
    public function hasUserSharedOnPlatform(int $blogPostId, int $userId, string $platform): bool
    {
        return $this->shareRepository->hasUserSharedOnPlatform($blogPostId, $userId, $platform);
    }

    /**
     * Get total shares for a blog post
     */
    public function getTotalShares(int $blogPostId): int
    {
        return $this->shareRepository->getTotalShares($blogPostId);
    }

    /**
     * Get share count for a specific platform
     */
    public function getShareCountByPlatform(int $blogPostId, string $platform): int
    {
        return $this->shareRepository->getShareCountByPlatform($blogPostId, $platform);
    }

    /**
     * Generate social sharing URLs
     */
    public function generateSharingUrls(BlogPost $blogPost): array
    {
        $url = request()->url();
        $title = urlencode($blogPost->title);
        $excerpt = urlencode($blogPost->excerpt ?? '');

        return [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url),
            'twitter' => "https://twitter.com/intent/tweet?url=" . urlencode($url) . "&text=" . $title,
            'email' => "mailto:?subject=" . $title . "&body=" . $excerpt . "%0A%0ARead more: " . $url,
        ];
    }

    /**
     * Process social media share
     */
    public function processShare(int $blogPostId, string $platform, Request $request, int $userId): array
    {
        try {
            if ($userId && $this->hasUserSharedOnPlatform($blogPostId, $userId, $platform)) {
                return [
                    'success' => false,
                    'message' => 'You have already shared this post on ' . ucfirst($platform),
                    'shares_count' => $this->getShareCountByPlatform($blogPostId, $platform)
                ];
            }

            $share = $this->recordShare($blogPostId, $platform, $request, $userId);

            $statistics = $this->getShareStatistics($blogPostId);

            Log::info('Social media share processed successfully', [
                'blog_post_id' => $blogPostId,
                'platform' => $platform,
                'user_id' => $userId,
                'share_id' => $share->id
            ]);

            return [
                'success' => true,
                'message' => 'Share recorded successfully',
                'shares_count' => $statistics[$platform],
                'total_shares' => $statistics['total']
            ];
        } catch (Exception $e) {
            Log::error('Error processing social media share', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPostId,
                'platform' => $platform
            ]);

            return [
                'success' => false,
                'message' => 'Error processing share. Please try again.',
                'shares_count' => $this->getShareCountByPlatform($blogPostId, $platform)
            ];
        }
    }
}
