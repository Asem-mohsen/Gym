function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('URL copied to clipboard!');
    }, function(err) {
        toastr.error('Could not copy text: ', err);
    });
}

// Initialize notification system
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    updateNotificationBadge();
    
    // Set up real-time notifications with Pusher
    initializePusher();
});

function loadNotifications() {
    fetch('/admin/notifications/user-notifications')
        .then(response => response.json())
        .then(data => {
            if (data.notifications) {
                displayNotifications(data.notifications);
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notifications-list').innerHTML = 
                '<div class="text-center text-muted py-4">Error loading notifications</div>';
        });
}

function displayNotifications(notifications) {
    const container = document.getElementById('notifications-list');
    
    if (notifications.length === 0) {
        container.innerHTML = '<div class="text-center text-muted py-4">No notifications</div>';
        return;
    }
    
    let html = '';
    notifications.slice(0, 5).forEach(notification => {
        const isRead = notification.read_at ? 'opacity-50' : '';
        const priorityClass = getPriorityClass(notification.data.priority || 'normal');
        
        html += `
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
    });
    
    container.innerHTML = html;
}

function updateNotificationBadge() {
    fetch('/admin/notifications/user-notifications')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notification-badge');
            const unreadCount = data.notifications ? data.notifications.filter(n => !n.read_at).length : 0;
            
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error updating notification badge:', error);
        });
}

function markNotificationAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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

    const pusher = new Pusher('{{ config("services.pusher.key") }}', {
        cluster: '{{ config("services.pusher.cluster") }}',
        encrypted: true
    });

    const channel = pusher.subscribe('private-user.{{ auth()->id() }}');
    
    channel.bind('notification.sent', function(data) {
        // Show toast notification
        toastr.info(data.notification.data.message || 'New notification received', data.notification.data.subject || 'Notification');
        
        // Reload notifications
        loadNotifications();
        updateNotificationBadge();
    });
}