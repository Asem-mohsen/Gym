<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CommentRepository
{
    /**
     * Get approved comments for a blog post with their replies
     */
    public function getCommentsForBlogPost(int $blogPostId, int $perPage = 10): LengthAwarePaginator
    {
        return Comment::with(['user', 'children.user', 'children.likes', 'likes'])
            ->withCount(['likes', 'children'])
            ->where('blog_post_id', $blogPostId)
            ->whereNull('parent_id')
            ->approved()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all comments for a blog post (admin use)
     */
    public function getAllCommentsForBlogPost(int $blogPostId): Collection
    {
        return Comment::with(['user', 'children.user', 'likes'])
            ->where('blog_post_id', $blogPostId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create a new comment
     */
    public function createComment(array $data): Comment
    {
        return DB::transaction(function () use ($data) {
            $comment = Comment::create($data);
            return $comment->load('user');
        });
    }

    /**
     * Update an existing comment
     */
    public function updateComment(Comment $comment, array $data): Comment
    {
        return DB::transaction(function () use ($comment, $data) {
            $comment->update($data);
            return $comment->fresh();
        });
    }

    /**
     * Delete a comment and its replies
     */
    public function deleteComment(Comment $comment): bool
    {
        return DB::transaction(function () use ($comment) {
            // Delete replies first
            $comment->children()->delete();
            // Delete the comment
            return $comment->delete();
        });
    }

    /**
     * Find a comment by ID with relationships
     */
    public function findById(int $id, array $with = []): ?Comment
    {
        return Comment::with($with)->find($id);
    }

    /**
     * Approve a comment
     */
    public function approveComment(Comment $comment): Comment
    {
        $comment->update(['status' => 'approved']);
        return $comment;
    }

    /**
     * Mark comment as spam
     */
    public function markCommentAsSpam(Comment $comment): Comment
    {
        $comment->update(['status' => 'spam']);
        return $comment;
    }

    /**
     * Get comments by status
     */
    public function getCommentsByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return Comment::with(['user', 'blogPost'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get comment statistics
     */
    public function getCommentStatistics(): array
    {
        return [
            'total' => Comment::count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
        ];
    }
}
