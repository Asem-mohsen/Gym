<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get approved comments for a blog post
     */
    public function getCommentsForBlogPost(int $blogPostId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->commentRepository->getCommentsForBlogPost($blogPostId, $perPage);
    }

    /**
     * Create a new comment
     */
    public function createComment(array $data, int $blogPostId, ?int $userId = null): Comment
    {
        try {
            $commentData = [
                'blog_post_id' => $blogPostId,
                'user_id' => $userId,
                'parent_id' => $data['parent_id'] ?? null,
                'content' => $data['content'],
                'status' => $userId ? 'approved' : 'pending', // Auto-approve authenticated users
            ];

            $comment = $this->commentRepository->createComment($commentData);

            Log::info('Comment created successfully', [
                'comment_id' => $comment->id,
                'blog_post_id' => $blogPostId,
                'user_id' => $userId
            ]);

            return $comment;
        } catch (Exception $e) {
            Log::error('Error creating comment', [
                'message' => $e->getMessage(),
                'data' => $data,
                'blog_post_id' => $blogPostId
            ]);
            throw $e;
        }
    }

    /**
     * Create a reply to a comment
     */
    public function createReply(array $data, int $parentCommentId, int $blogPostId, ?int $userId = null): Comment
    {
        try {
            $replyData = [
                'blog_post_id' => $blogPostId,
                'user_id' => $userId,
                'parent_id' => $parentCommentId,
                'content' => $data['content'],
                'status' => $userId ? 'approved' : 'pending',
            ];

            $reply = $this->commentRepository->createComment($replyData);

            Log::info('Reply created successfully', [
                'reply_id' => $reply->id,
                'parent_comment_id' => $parentCommentId,
                'blog_post_id' => $blogPostId
            ]);

            return $reply;
        } catch (Exception $e) {
            Log::error('Error creating reply', [
                'message' => $e->getMessage(),
                'data' => $data,
                'parent_comment_id' => $parentCommentId
            ]);
            throw $e;
        }
    }

    /**
     * Update a comment
     */
    public function updateComment(Comment $comment, array $data): Comment
    {
        try {
            $updatedComment = $this->commentRepository->updateComment($comment, $data);

            Log::info('Comment updated successfully', [
                'comment_id' => $comment->id,
                'changes' => $data
            ]);

            return $updatedComment;
        } catch (Exception $e) {
            Log::error('Error updating comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id,
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Comment $comment): bool
    {
        try {
            $result = $this->commentRepository->deleteComment($comment);

            Log::info('Comment deleted successfully', [
                'comment_id' => $comment->id
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('Error deleting comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);
            throw $e;
        }
    }

    /**
     * Toggle like on a comment
     */
    public function toggleLike(Comment $comment, User $user): array
    {
        try {
            $isLiked = $comment->isLikedBy($user);

            if ($isLiked) {
                $comment->likes()->detach($user->id);
                $action = 'unliked';
            } else {
                $comment->likes()->attach($user->id);
                $action = 'liked';
            }

            $comment->refresh();
            $likesCount = $comment->likes_count;

            Log::info('Comment like toggled', [
                'comment_id' => $comment->id,
                'user_id' => $user->id,
                'action' => $action,
                'likes_count' => $likesCount
            ]);

            return [
                'action' => $action,
                'likes_count' => $likesCount,
                'is_liked' => !$isLiked
            ];
        } catch (Exception $e) {
            Log::error('Error toggling comment like', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id,
                'user_id' => $user->id
            ]);
            throw $e;
        }
    }

    /**
     * Approve a comment
     */
    public function approveComment(Comment $comment): Comment
    {
        try {
            $approvedComment = $this->commentRepository->approveComment($comment);

            Log::info('Comment approved', [
                'comment_id' => $comment->id
            ]);

            return $approvedComment;
        } catch (Exception $e) {
            Log::error('Error approving comment', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);
            throw $e;
        }
    }

    /**
     * Mark comment as spam
     */
    public function markCommentAsSpam(Comment $comment): Comment
    {
        try {
            $spamComment = $this->commentRepository->markCommentAsSpam($comment);

            Log::info('Comment marked as spam', [
                'comment_id' => $comment->id
            ]);

            return $spamComment;
        } catch (Exception $e) {
            Log::error('Error marking comment as spam', [
                'message' => $e->getMessage(),
                'comment_id' => $comment->id
            ]);
            throw $e;
        }
    }

    /**
     * Get comment statistics
     */
    public function getCommentStatistics(): array
    {
        return $this->commentRepository->getCommentStatistics();
    }
}
