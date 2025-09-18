<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{BlogPost, SiteSetting, Comment};
use App\Services\{BlogService, CommentService, BlogPostShareService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Comments\StoreCommentRequest;
use Exception;

class BlogController extends Controller
{
    protected $blogService;
    protected $commentService;
    protected $shareService;

    public function __construct(
        BlogService $blogService,
        CommentService $commentService,
        BlogPostShareService $shareService
    ) {
        $this->blogService = $blogService;
        $this->commentService = $commentService;
        $this->shareService = $shareService;
    }

    public function index(Request $request, SiteSetting $siteSetting)
    {
        $blogPosts = $this->blogService->getBlogPosts(siteSettingId: $siteSetting->id, isPublished: true, perPage: 5, orderBy: 'created_at', orderByDirection: 'desc');
        $futurePosts =  $this->blogService->getBlogPosts(siteSettingId: $siteSetting->id, isPublished: true, take: 5, orderBy: 'created_at', orderByDirection: 'desc');
        $categories = $this->blogService->getCategories(withCount: ['blogPosts']);
        $tags = $this->blogService->getTags(withCount: ['blogPosts']);
        
        return view('user.blog' , compact('blogPosts' , 'categories' , 'tags', 'futurePosts'));
    }

    public function show(SiteSetting $siteSetting, BlogPost $blogPost)
    {
        $comments = $this->commentService->getCommentsForBlogPost($blogPost->id, 10);
        
        $shareStatistics = $this->shareService->getShareStatistics($blogPost->id);
        
        $sharingUrls = $this->shareService->generateSharingUrls($blogPost);
        
        $blogPost = $this->blogService->showBlogPost($blogPost->id, ['media']);
        
        return view('user.blog-details', compact(
            'blogPost', 
            'comments', 
            'shareStatistics', 
            'sharingUrls'
        ));
    }

    /**
     * Store a new comment
     */
    public function storeComment(StoreCommentRequest $request, SiteSetting $siteSetting, BlogPost $blogPost)
    {
        $validated = $request->validated();

        try {
            $userId = Auth::id();
            
             $this->commentService->createComment(['content' => $validated['content']],$blogPost->id,$userId);

            return redirect()->back()->with('success', 'Comment posted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error posting comment. Please try again.');
        }
    }

    /**
     * Store a reply to a comment
     */
    public function storeReply(StoreCommentRequest $request, SiteSetting $siteSetting, BlogPost $blogPost, Comment $comment)
    {
        $validated = $request->validated();

        try {
            $userId = Auth::id();
            
            $reply = $this->commentService->createReply(
                ['content' => $validated['content']],
                $comment->id,
                $blogPost->id,
                $userId
            );

            return redirect()->back()->with('success', 'Reply posted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error posting reply. Please try again.');
        }
    }

    /**
     * Toggle like on a comment
     */
    public function toggleLike(Request $request, SiteSetting $siteSetting, BlogPost $blogPost, Comment $comment)
    {
        try {
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'You must be logged in to like comments.');
            }

            $result = $this->commentService->toggleLike($comment, Auth::user());

            return redirect()->back()->with('success', 'Comment ' . $result['action'] . ' successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error processing like. Please try again.');
        }
    }

    /**
     * Share a blog post
     */
    public function share(Request $request, SiteSetting $siteSetting, BlogPost $blogPost)
    {
        $request->validate([
            'platform' => 'required|in:facebook,twitter,email',
        ]);

        try {
            $result = $this->shareService->processShare($blogPost->id, $request->platform, $request, Auth::id());

            if ($result['success']) {
                return redirect()->back()->with('success', $result['message']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error processing share. Please try again.');
        }
    }
}
