class BlogComments {
    constructor() {
        this.currentUser = window.currentUser || null;
        this.blogPostId = document.querySelector('[data-blog-post-id]')?.dataset.blogPostId;
        this.commentForm = document.getElementById('comment-form');
        this.commentsContainer = document.getElementById('comments-container');
        this.replyForms = document.querySelectorAll('.reply-form form');
        this.shareButtons = document.querySelectorAll('.share-btn');
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateShareCounts();
    }

    bindEvents() {
        if (this.commentForm) {
            this.commentForm.addEventListener('submit', (e) => this.handleCommentSubmit(e));
        }

        this.replyForms.forEach((form, index) => {
            form.addEventListener('submit', (e) => this.handleReplySubmit(e));
        });

        this.shareButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleShare(e));
        });

        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('like-btn') || e.target.closest('.like-btn')) {
                const likeBtn = e.target.classList.contains('like-btn') ? e.target : e.target.closest('.like-btn');
                this.handleLike(likeBtn);
            }
            
            if (e.target.classList.contains('reply-btn') || e.target.closest('.reply-btn')) {
                const replyBtn = e.target.classList.contains('reply-btn') ? e.target : e.target.closest('.reply-btn');
                this.showReplyForm(replyBtn);
            }
        });
    }

    async handleCommentSubmit(e) {
        e.preventDefault();
        
        if (!this.currentUser) {
            this.showLoginPrompt();
            return;
        }

        const formData = new FormData(this.commentForm);
        const content = formData.get('content').trim();
        
        if (!content) {
            this.showError('Please enter a comment.');
            return;
        }

        try {
            const response = await fetch(`/gym/${window.gymSlug}/blog/${this.blogPostId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    content: content
                })
            });

            if (response.ok) {
                this.commentForm.reset();
                this.loadComments();
                this.showSuccess('Comment posted successfully!');
            } else {
                const result = await response.json();
                this.showError(result.message || 'Error posting comment.');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error posting comment. Please try again.');
        }
    }

    async handleReplySubmit(e) {
        e.preventDefault();
        
        if (!this.currentUser) {
            this.showLoginPrompt();
            return;
        }

        const form = e.target;
        const commentId = form.dataset.commentId;
        const content = form.querySelector('textarea[name="content"]').value.trim();
        
        if (!content) {
            this.showError('Please enter a reply.');
            return;
        }

        if (!commentId) {
            this.showError('Comment ID not found. Please try again.');
            return;
        }

        try {
            const response = await fetch(`/gym/${window.gymSlug}/blog/${this.blogPostId}/comments/${commentId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    content: content
                })
            });

            if (response.ok) {
                form.reset();
                this.loadComments();
                this.showSuccess('Reply posted successfully!');
            } else {
                const result = await response.json();
                this.showError(result.message || 'Error posting reply.');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error posting reply. Please try again.');
        }
    }

    async handleLike(button) {
        
        if (!this.currentUser) {
            this.showLoginPrompt();
            return;
        }

        const commentId = button.dataset.commentId;
        const likeCount = button.querySelector('.like-count');
        
        if (!commentId) {
            this.showError('Comment ID not found. Please try again.');
            return;
        }
        
        if (!likeCount) {
            this.showError('Like count element not found. Please try again.');
            return;
        }
        
        try {
            const response = await fetch(`/gym/${window.gymSlug}/blog/${this.blogPostId}/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });

            if (response.ok) {
                const isLiked = button.classList.contains('liked');
                const currentCount = parseInt(likeCount.textContent) || 0;
                
                if (isLiked) {
                    button.classList.remove('liked');
                    button.innerHTML = '<i class="fa fa-heart-o"></i> <span class="like-count">' + (currentCount - 1) + '</span>';
                } else {
                    button.classList.add('liked');
                    button.innerHTML = '<i class="fa fa-heart"></i> <span class="like-count">' + (currentCount + 1) + '</span>';
                }
            } else {
                const result = await response.json();
                this.showError(result.message || 'Error processing like.');
            }
        } catch (error) {
            this.showError('Error processing like. Please try again.');
        }
    }

    async handleShare(e) {
        e.preventDefault();
        
        const button = e.target;
        const platform = button.dataset.platform;
        const shareCount = button.querySelector('.share-count');
        
        try {
            const response = await fetch(`/gym/${window.gymSlug}/blog/${this.blogPostId}/shares`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    platform: platform
                })
            });

            if (response.ok) {
                const currentCount = parseInt(shareCount.textContent);
                shareCount.textContent = currentCount + 1;
                
                if (window.sharingUrls && window.sharingUrls[platform]) {
                    window.open(window.sharingUrls[platform], '_blank', 'width=600,height=400');
                }
                
                this.showSuccess('Share recorded successfully!');
            } else {
                const result = await response.json();
                this.showError(result.message || 'Error processing share.');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error processing share. Please try again.');
        }
    }

    async loadComments() {
        try {
            const response = await fetch(`/gym/${window.gymSlug}/blog/${this.blogPostId}`);
            const html = await response.text();
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            
            const newCommentsContainer = tempDiv.querySelector('#comments-container');
            if (newCommentsContainer && this.commentsContainer) {
                this.commentsContainer.innerHTML = newCommentsContainer.innerHTML;
                
                this.replyForms = document.querySelectorAll('.reply-form form');
                
                this.bindReplyEvents();
            }
        } catch (error) {
            console.error('Error loading comments:', error);
        }
    }

    showReplyForm(button) {
        const commentId = button.dataset.commentId;
        
        const replyFormDiv = document.querySelector(`[data-comment-id="${commentId}"] .reply-form`);
        
        if (replyFormDiv) {
            replyFormDiv.style.display = replyFormDiv.style.display === 'none' ? 'block' : 'none';
        }
    }

    bindReplyEvents() {
        const replyForms = document.querySelectorAll('.reply-form form');
        replyForms.forEach((form, index) => {
            form.removeEventListener('submit', this.handleReplySubmit);
            form.addEventListener('submit', (e) => this.handleReplySubmit(e));
        });
    }

    updateShareCounts() {
        this.shareButtons.forEach(button => {
            const platform = button.dataset.platform;
            const shareCount = button.querySelector('.share-count');
            
            if (window.shareStatistics && window.shareStatistics[platform]) {
                shareCount.textContent = window.shareStatistics[platform];
            }
        });
    }

    showSuccess(message) {
        if (window.toastr) {
            toastr.success(message);
        }
    }

    showError(message) {
        if (window.toastr) {
            toastr.error(message);
        }
    }

    showLoginPrompt() {
        if (window.toastr) {
            toastr.error('Please log in to perform this action.');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new BlogComments();
});
