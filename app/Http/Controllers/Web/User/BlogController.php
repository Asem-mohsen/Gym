<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Services\{SiteSettingService, BlogService, CommentService, BlogPostShareService};
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Exception;

class BlogController extends Controller
{
    protected $siteSettingId;
    protected $blogService;
    protected $commentService;
    protected $shareService;

    public function __construct(
        SiteSettingService $siteSettingService, 
        BlogService $blogService,
        CommentService $commentService,
        BlogPostShareService $shareService
    ) {
        $this->siteSettingId = $siteSettingService->getCurrentSiteSettingId();
        $this->blogService = $blogService;
        $this->commentService = $commentService;
        $this->shareService = $shareService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $blogPosts = $this->blogService->getBlogPosts($this->siteSettingId, true);
        $categories = $this->blogService->getCategories(withCount: ['blogPosts']);
        $tags = $this->blogService->getTags(withCount: ['blogPosts']);
        
        return view('user.blog' , compact('blogPosts' , 'categories' , 'tags'));
    }

    public function show(SiteSetting $siteSetting, BlogPost $blogPost)
    {
        $comments = $this->commentService->getCommentsForBlogPost($blogPost->id, 10);
        
        $shareStatistics = $this->shareService->getShareStatistics($blogPost->id);
        
        $sharingUrls = $this->shareService->generateSharingUrls($blogPost);
        
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
    public function storeComment(Request $request, SiteSetting $siteSetting, BlogPost $blogPost)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        try {
            $userId = Auth::id();
            
            $comment = $this->commentService->createComment(
                ['content' => $request->content],
                $blogPost->id,
                $userId
            );

            return redirect()->back()->with('success', 'Comment posted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error posting comment. Please try again.');
        }
    }

    /**
     * Store a reply to a comment
     */
    public function storeReply(Request $request, SiteSetting $siteSetting, BlogPost $blogPost, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        try {
            $userId = Auth::id();
            
            $reply = $this->commentService->createReply(
                ['content' => $request->content],
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
            $result = $this->shareService->processShare($blogPost->id, $request->platform, $request);

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
