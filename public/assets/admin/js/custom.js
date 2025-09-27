function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('URL copied to clipboard!');
    }, function(err) {
        toastr.error('Could not copy text: ', err);
    });
}

// Initialize notification system
let currentPage = 1;
let isLoading = false;
let hasMoreNotifications = true;

document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    updateNotificationBadge();
    
    // Set up real-time notifications with Pusher
    initializePusher();
    
    // Set up infinite scroll
    setupInfiniteScroll();
});

function loadNotifications(page = 1, append = false) {
    if (isLoading) return;
    
    isLoading = true;
    
    fetch(`/admin/notifications/user-notifications?page=${page}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            // Check if response is JSON, if not, don't process it
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Notification endpoint returned non-JSON response, skipping notification load');
                return null;
            }
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data) return; // Skip if no data
            
            if (data.notifications) {
                if (append) {
                    appendNotifications(data.notifications);
                } else {
                    displayNotifications(data.notifications);
                }
                
                // Check if there are more notifications
                hasMoreNotifications = data.notifications.length >= 10;
                currentPage = page;
            } else {
                if (!append) {
                    const container = document.getElementById('notifications-list');
                    if (container) {
                        container.innerHTML = 
                            '<div class="text-center text-muted py-4">No notifications</div>';
                    }
                }
                hasMoreNotifications = false;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            if (!append) {
                const container = document.getElementById('notifications-list');
                if (container) {
                    container.innerHTML = 
                        '<div class="text-center text-muted py-4">Error loading notifications</div>';
                }
            }
        })
        .finally(() => {
            isLoading = false;
        });
}

function displayNotifications(notifications) {
    const container = document.getElementById('notifications-list');
    
    if (notifications.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-4">No notifications</div>';
        return;
    }
    
    let html = '';
    notifications.forEach(notification => {
        html += createNotificationHTML(notification);
    });
    
    // Add loading indicator if there are more notifications
    if (hasMoreNotifications) {
        html += '<div id="loading-indicator" class="text-center py-2" style="display: none;"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';
    }
    
    container.innerHTML = html;
}

function appendNotifications(notifications) {
    const container = document.getElementById('notifications-list');
    const loadingIndicator = document.getElementById('loading-indicator');
    
    if (loadingIndicator) {
        loadingIndicator.style.display = 'none';
    }
    
    let html = '';
    notifications.forEach(notification => {
        html += createNotificationHTML(notification);
    });
    
    // Add loading indicator if there are more notifications
    if (hasMoreNotifications) {
        html += '<div id="loading-indicator" class="text-center py-2" style="display: none;"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';
    }
    
    container.insertAdjacentHTML('beforeend', html);
}

function createNotificationHTML(notification) {
    const isRead = notification.read_at ? 'opacity-50' : '';
    const priorityClass = getPriorityClass(notification.data.priority || 'normal');
    
    return `
        <div class="notification-item mb-2 p-2 border-start border-3 ${priorityClass} ${isRead}" onclick="markNotificationAsRead('${notification.id}')">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold fs-7">${notification.data.subject || 'Notification'}</h6>
                    <p class="mb-1 small text-muted">${notification.data.message ? notification.data.message.substring(0, 50) + '...' : ''}</p>
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        ${formatDate(notification.created_at)}
                    </small>
                </div>
                ${!notification.read_at ? '<div class="ms-2"><span class="badge badge-circle badge-danger"></span></div>' : ''}
            </div>
        </div>
    `;
}

function updateNotificationBadge() {
    // Only update badge if we're not already on the notifications page to prevent redirect loops
    if (window.location.pathname.includes('/admin/notifications/user-notifications')) {
        return;
    }
    
    fetch('/admin/notifications/user-notifications', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            // Check if response is JSON, if not, don't process it
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.warn('Notification endpoint returned non-JSON response, skipping badge update');
                return null;
            }
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data) return; // Skip if no data
            
            const badge = document.getElementById('notification-badge');
            if (badge) {
                const unreadCount = data.notifications ? data.notifications.filter(n => !n.read_at).length : 0;
                
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error updating notification badge:', error);
            // Don't redirect or show user-facing errors for badge updates
        });
}

function markNotificationAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is JSON, if not, don't process it
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.warn('Mark read endpoint returned non-JSON response');
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            loadNotifications();
            updateNotificationBadge();
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
    });
}

function markAllNotificationsAsRead() {
    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Check if response is JSON, if not, don't process it
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.warn('Mark all read endpoint returned non-JSON response');
            return null;
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            loadNotifications();
            updateNotificationBadge();
        }
    })
    .catch(error => {
        console.error('Error marking all notifications as read:', error);
    });
}

function getPriorityClass(priority) {
    switch(priority) {
        case 'urgent': return 'border-danger';
        case 'high': return 'border-warning';
        case 'normal': return 'border-info';
        case 'low': return 'border-secondary';
        default: return 'border-info';
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) {
        return 'Yesterday';
    } else if (diffDays < 1) {
        const diffHours = Math.ceil(diffTime / (1000 * 60 * 60));
        if (diffHours < 1) {
            const diffMinutes = Math.ceil(diffTime / (1000 * 60));
            return diffMinutes + ' minutes ago';
        }
        return diffHours + ' hours ago';
    } else if (diffDays < 7) {
        return diffDays + ' days ago';
    } else {
        return date.toLocaleDateString();
    }
}

function initializePusher() {
    // Check if Pusher is available
    if (typeof Pusher === 'undefined') {
        console.warn('Pusher not loaded. Real-time notifications disabled.');
        return;
    }

    // Check if Echo is available (from our Vite build)
    if (typeof window.Echo === 'undefined') {
        console.warn('Laravel Echo not loaded. Real-time notifications disabled.');
        return;
    }

    // Use Laravel Echo for real-time notifications
    window.Echo.channel('notifications')
        .listen('notification.sent', function(data) {
            // Show toast notification
            if (typeof toastr !== 'undefined') {
                toastr.info(data.notification.message || 'New notification received', data.notification.title || 'Notification');
            }
            
            // Reload notifications and update badge (only if not on notifications page)
            if (!window.location.pathname.includes('/admin/notifications/user-notifications')) {
                loadNotifications();
                updateNotificationBadge();
            }
        });
}

function setupInfiniteScroll() {
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    if (!notificationsDropdown) return;
    
    notificationsDropdown.addEventListener('scroll', function() {
        const { scrollTop, scrollHeight, clientHeight } = this;
        
        // Check if user has scrolled to bottom (with 50px threshold)
        if (scrollTop + clientHeight >= scrollHeight - 50) {
            loadMoreNotifications();
        }
    });
}

function loadMoreNotifications() {
    if (isLoading || !hasMoreNotifications) return;
    
    const loadingIndicator = document.getElementById('loading-indicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'block';
    }
    
    loadNotifications(currentPage + 1, true);
}