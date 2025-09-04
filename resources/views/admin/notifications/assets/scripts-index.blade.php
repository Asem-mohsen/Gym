<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadRecentNotifications();
        loadRecentSentNotifications();
        loadRecentSystemNotifications();
    });
    
    function loadRecentNotifications() {
        fetch('/admin/notifications/recent')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRecentNotifications(data.data);
                }
            })
            .catch(error => {
                document.getElementById('recent-notifications').innerHTML = 
                    '<div class="text-center text-muted">Error loading notifications</div>';
            });
    }
    
    function displayRecentNotifications(data) {
        const container = document.getElementById('recent-notifications');
        
        if (data.notifications.length === 0) {
            container.innerHTML = '<div class="text-center text-muted">No recent notifications</div>';
            return;
        }
        
        let html = '';
        data.notifications.forEach(notification => {
            const priorityClass = getPriorityClass(notification.data.priority || 'normal');
            const priorityIcon = getPriorityIcon(notification.data.priority || 'normal');
            
            html += `
                <div class="notification-item mb-3 p-2 border-left ${priorityClass}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold">${notification.data.subject || 'Notification'}</h6>
                            <p class="mb-1 small text-muted">${notification.data.message || ''}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                ${formatDate(notification.created_at)}
                            </small>
                        </div>
                        <div class="ml-2">
                            <i class="${priorityIcon} ${priorityClass}"></i>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
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
    
    function getPriorityIcon(priority) {
        switch(priority) {
            case 'urgent': return 'fas fa-exclamation-triangle';
            case 'high': return 'fas fa-exclamation-circle';
            case 'normal': return 'fas fa-info-circle';
            case 'low': return 'fas fa-bell';
            default: return 'fas fa-info-circle';
        }
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) return 'Today';
        if (diffDays === 2) return 'Yesterday';
        if (diffDays <= 7) return `${diffDays - 1} days ago`;
        
        return date.toLocaleDateString();
    }
    
    function loadRecentSentNotifications() {
        fetch('/admin/notifications/recent-sent-by-type')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRecentSentNotifications(data.data);
                }
            })
            .catch(error => {
                document.getElementById('recent-sent-notifications').innerHTML = 
                    '<div class="text-center text-muted">Error loading sent notifications</div>';
            });
    }
    
    function displayRecentSentNotifications(data) {
        const container = document.getElementById('recent-sent-notifications');
        
        if (data.notifications.length === 0) {
            container.innerHTML = '<div class="text-center text-muted">No sent notifications</div>';
            return;
        }
        
        let html = '';
        data.notifications.forEach(notification => {
            const priorityClass = getPriorityClass(notification.data.priority || 'normal');
            const priorityIcon = getPriorityIcon(notification.data.priority || 'normal');
            
            html += `
                <div class="notification-item mb-3 p-2 border-left ${priorityClass}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold">${notification.data.subject || 'Notification'}</h6>
                            <p class="mb-1 small text-muted">${notification.data.message || ''}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                ${formatDate(notification.created_at)}
                            </small>
                        </div>
                        <div class="ml-2">
                            <i class="${priorityIcon} ${priorityClass}"></i>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    function loadRecentSystemNotifications() {
        fetch('/admin/notifications/recent-system')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRecentSystemNotifications(data.data);
                }
            })
            .catch(error => {
                document.getElementById('recent-system-notifications').innerHTML = 
                    '<div class="text-center text-muted">Error loading system notifications</div>';
            });
    }
    
    function displayRecentSystemNotifications(data) {
        const container = document.getElementById('recent-system-notifications');
        
        if (data.notifications.length === 0) {
            container.innerHTML = '<div class="text-center text-muted">No system notifications</div>';
            return;
        }
        
        let html = '';
        data.notifications.forEach(notification => {
            const priorityClass = getPriorityClass(notification.data.priority || 'normal');
            const priorityIcon = getPriorityIcon(notification.data.priority || 'normal');
            
            html += `
                <div class="notification-item mb-3 p-2 border-left ${priorityClass}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold">${notification.data.subject || 'System Notification'}</h6>
                            <p class="mb-1 small text-muted">${notification.data.message || ''}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                ${formatDate(notification.created_at)}
                            </small>
                        </div>
                        <div class="ml-2">
                            <i class="${priorityIcon} ${priorityClass}"></i>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    function markAllAsRead() {
        fetch('/api/v1/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadRecentNotifications();
                toastr.success('All notifications marked as read');
            } else {
                toastr.error(data.message || 'Failed to mark notifications as read');
            }
        })
        .catch(error => {
            toastr.error('Error marking notifications as read');
        });
    }
    
    function useTemplate(type) {
        let subject = '';
        let message = '';
        
        switch(type) {
            case 'holiday':
                subject = 'Gym Holiday Notice';
                message = 'Dear valued members, please note that our gym will be closed for the upcoming holiday. We apologize for any inconvenience and look forward to seeing you back soon!';
                break;
            case 'maintenance':
                subject = 'Facility Maintenance Notice';
                message = 'We will be performing scheduled maintenance on some of our equipment. Please check with our staff for availability updates. Thank you for your understanding.';
                break;
            case 'offer':
                subject = 'Special Offer - Limited Time!';
                message = 'Don\'t miss out on our exclusive offer! Contact our staff for details on this limited-time promotion.';
                break;
        }
        
        // Store in session storage for the create form
        sessionStorage.setItem('notificationTemplate', JSON.stringify({ subject, message }));
        
        // Redirect to create form
        window.location.href = '{{ route("admin.notifications.create") }}';
    }
</script>