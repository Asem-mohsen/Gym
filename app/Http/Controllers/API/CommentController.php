<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\StoreCommentRequest;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
    public function store(StoreCommentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = Auth::id();

            $comment = $this->commentService->createComment(
                $validated,
                $validated['blog_post_id'],
                $userId
            );

            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully',
                'data' => [
                    'comment' => $comment->load('user'),
                    'is_authenticated' => !is_null($userId)
                ]
            ], 201);
        } catch (Exception $e) {
            Log::error('Error storing comment', [
                'message' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error posting comment. Please try again.',
            ], 500);
        }
    }

    /**
     * Store a reply to a comment
     */
    public function reply(StoreCommentRequest $request, Comment $comment): JsonResponse
    {
        try {
            $validated = $request->validated();
            $userId = Auth::id();

            $reply = $this->commentService->createReply(
                $validated,
                $comment->id,
                $validated['blog_post_id'],
                $userId
            );

            return response()->json([
                'success' => true,
                'message' => 'Reply posted successfully',
                'data' => [
                    'reply' => $reply->load('user'),
                    'is_authenticated' => !is_null($userId)
                ]
            ], 201);
        } catch (Exception $e) {
            Log::error('Error storing reply', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id,
                'data' => $request->validated()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error posting reply. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle like on a comment
     */
    public function toggleLike(Comment $comment): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to like comments.',
                ], 401);
            }

            $result = $this->commentService->toggleLike($comment, Auth::user());

            return response()->json([
                'success' => true,
                'message' => 'Comment ' . $result['action'] . ' successfully',
                'data' => $result
            ]);
        } catch (Exception $e) {
            Log::error('Error toggling comment like', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing like. Please try again.',
            ], 500);
        }
    }

    /**
     * Get comments for a blog post
     */
    public function getComments(BlogPost $blogPost, Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $comments = $this->commentService->getCommentsForBlogPost($blogPost->id, $perPage);

            return response()->json([
                'success' => true,
                'data' => $comments
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching comments', [
                'message' => $e->getMessage(),
                'blog_post_id' => $blogPost->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching comments. Please try again.',
            ], 500);
        }
    }

    /**
     * Update a comment
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        try {
            if (!Auth::check() || Auth::id() !== $comment->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to edit this comment.',
                ], 403);
            }

            $validated = $request->validate([
                'content' => 'required|string|min:3|max:1000',
            ]);

            $updatedComment = $this->commentService->updateComment($comment, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'data' => $updatedComment->load('user')
            ]);
        } catch (Exception $e) {
            Log::error('Error updating comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error updating comment. Please try again.',
            ], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment): JsonResponse
    {
        try {
            if (!Auth::check() || Auth::id() !== $comment->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this comment.',
                ], 403);
            }

            $this->commentService->deleteComment($comment);

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error deleting comment. Please try again.',
            ], 500);
        }
    }
}
