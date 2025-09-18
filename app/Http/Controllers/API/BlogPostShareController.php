<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Services\BlogPostShareService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class BlogPostShareController extends Controller
{
    public function __construct(protected BlogPostShareService $shareService)
    {
        $this->shareService = $shareService;
    }

    /**
     * Share a blog post on social media
     */
    public function share(Request $request, SiteSetting $gym, BlogPost $blogPost): JsonResponse
    {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return failureResponse('You must be logged in to share posts.', 401);
            }

            $request->validate([
                'platform' => 'required|in:facebook,twitter,email',
            ]);

            $platform = $request->platform;
            $userId = Auth::guard('sanctum')->id();
            $result = $this->shareService->processShare($blogPost->id, $platform, $request, $userId);

            if ($result['success']) {
                return successResponse($result, 'Blog post shared successfully');
            } else {
                return failureResponse($result['message']);
            }
        } catch (Exception $e) {
            Log::error('Error sharing blog post', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id,
                'platform' => $request->platform ?? 'unknown'
            ]);

            return failureResponse('Error processing share. Please try again.');
        }
    }

    /**
     * Get sharing URLs for a blog post
     */
    public function getSharingUrls(SiteSetting $gym, BlogPost $blogPost): JsonResponse
    {
        try {
            $urls = $this->shareService->generateSharingUrls($blogPost);

            return successResponse($urls, 'Sharing URLs fetched successfully');
        } catch (Exception $e) {
            Log::error('Error generating sharing URLs', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id
            ]);

            return failureResponse('Error generating sharing URLs. Please try again.');
        }
    }
}
