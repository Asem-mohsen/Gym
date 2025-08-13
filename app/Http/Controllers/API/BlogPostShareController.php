<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\BlogPostShareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BlogPostShareController extends Controller
{
    public function __construct(protected BlogPostShareService $shareService)
    {
        $this->shareService = $shareService;
    }

    /**
     * Share a blog post on social media
     */
    public function share(Request $request, BlogPost $blogPost): JsonResponse
    {
        try {
            $request->validate([
                'platform' => 'required|in:facebook,twitter,email',
            ]);

            $platform = $request->platform;
            $result = $this->shareService->processShare($blogPost->id, $platform, $request);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'platform' => $platform,
                        'shares_count' => $result['shares_count'],
                        'total_shares' => $result['total_shares']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'data' => [
                        'platform' => $platform,
                        'shares_count' => $result['shares_count']
                    ]
                ], 400);
            }
        } catch (Exception $e) {
            Log::error('Error sharing blog post', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id,
                'platform' => $request->platform ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing share. Please try again.',
            ], 500);
        }
    }

    /**
     * Get share statistics for a blog post
     */
    public function getShareStatistics(BlogPost $blogPost): JsonResponse
    {
        try {
            $statistics = $this->shareService->getShareStatistics($blogPost->id);

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching share statistics', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching share statistics. Please try again.',
            ], 500);
        }
    }

    /**
     * Get sharing URLs for a blog post
     */
    public function getSharingUrls(BlogPost $blogPost): JsonResponse
    {
        try {
            $urls = $this->shareService->generateSharingUrls($blogPost);

            return response()->json([
                'success' => true,
                'data' => $urls
            ]);
        } catch (Exception $e) {
            Log::error('Error generating sharing URLs', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error generating sharing URLs. Please try again.',
            ], 500);
        }
    }
}
