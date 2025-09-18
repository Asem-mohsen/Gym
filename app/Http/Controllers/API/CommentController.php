<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\{BlogPost, Comment, SiteSetting};
use App\Services\CommentService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Log};
use Exception;

class CommentController extends Controller
{
    public function __construct(protected CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Store a new comment
     */
    public function store(StoreCommentRequest $request, SiteSetting $gym, BlogPost $blogPost): JsonResponse
    {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return failureResponse('You must be logged in to post comments.');
            }

            $validated = $request->validated();
            $userId = Auth::guard('sanctum')->id();

            $comment = $this->commentService->createComment(['content' => $validated['content']],$blogPost->id,$userId);

            return successResponse(new CommentResource($comment->load('user', 'likes')),'Comment posted successfully');
        } catch (Exception $e) {
            Log::error('Error storing comment', ['message' => $e->getMessage(),'data' => $request->validated()]);

            return failureResponse('Error posting comment. Please try again.');
        }
    }

    /**
     * Store a reply to a comment
     */
    public function reply(StoreCommentRequest $request,SiteSetting $gym, BlogPost $blogPost, Comment $comment): JsonResponse
    {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return failureResponse('You must be logged in to post replies.');
            }

            $validated = $request->validated();
            $userId = Auth::guard('sanctum')->id();

            $reply = $this->commentService->createReply(['content' => $validated['content']],$comment->id,$blogPost->id,$userId);

            return successResponse(new CommentResource($reply->load('user', 'likes')),'Reply posted successfully');
        } catch (Exception $e) {
            Log::error('Error storing reply', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id,
                'data' => $request->validated()
            ]);

            return failureResponse('Error posting reply. Please try again.');
        }
    }

    /**
     * Toggle like on a comment
     */
    public function toggleLike(SiteSetting $gym, BlogPost $blogPost, Comment $comment): JsonResponse
    {
        try {
            if (!Auth::guard('sanctum')->check()) {
                return failureResponse('You must be logged in to like comments.');
            }

            $result = $this->commentService->toggleLike($comment, Auth::guard('sanctum')->user());

            return successResponse($result,'Comment ' . $result['action'] . ' successfully');
        } catch (Exception $e) {
            Log::error('Error toggling comment like', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);

            return failureResponse('Error processing like. Please try again.');
        }
    }

    /**
     * Get comments for a blog post
     */
    public function getComments(Request $request, SiteSetting $gym, BlogPost $blogPost): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $comments = $this->commentService->getCommentsForBlogPost($blogPost->id, $perPage);

            return successResponse(CommentResource::collection($comments),'Comments fetched successfully');
        } catch (Exception $e) {
            Log::error('Error fetching comments', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id
            ]);

            return failureResponse('Error fetching comments. Please try again.');
        }
    }

    /**
     * Update a comment
     */
    public function update(Request $request,SiteSetting $gym, BlogPost $blogPost, Comment $comment): JsonResponse
    {
        try {
            if (!Auth::guard('sanctum')->check() || Auth::guard('sanctum')->id() !== $comment->user_id) {
                return failureResponse('Unauthorized to edit this comment.');
            }

            $validated = $request->validate([
                'content' => 'required|string|min:3|max:1000',
            ]);

            $updatedComment = $this->commentService->updateComment($comment, $validated);

            return successResponse(new CommentResource($updatedComment->load('user', 'likes')),'Comment updated successfully');
        } catch (Exception $e) {
            Log::error('Error updating comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);

            return failureResponse('Error updating comment. Please try again.');
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment): JsonResponse
    {
        try {
            if (!Auth::guard('sanctum')->check() || Auth::guard('sanctum')->id() !== $comment->user_id) {
                return failureResponse('Unauthorized to delete this comment.');
            }

            $this->commentService->deleteComment($comment);

            return successResponse('Comment deleted successfully');
        } catch (Exception $e) {
            Log::error('Error deleting comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);

            return failureResponse('Error deleting comment. Please try again.');
        }
    }
}
