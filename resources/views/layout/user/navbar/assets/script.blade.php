<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Profile Dropdown Toggle
        const userAvatar = document.getElementById('userProfileDropdown');
        const dropdownMenu = userAvatar?.nextElementSibling;
        
        if (userAvatar && dropdownMenu) {
            userAvatar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                
                if (isExpanded) {
                    dropdownMenu.classList.remove('show');
                } else {
                    dropdownMenu.classList.add('show');
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userAvatar.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    userAvatar.setAttribute('aria-expanded', 'false');
                    dropdownMenu.classList.remove('show');
                }
            });
        }

        // Notification functionality
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationBadgeMobile = document.getElementById('notificationBadgeMobile');
        const notificationList = document.getElementById('notificationList');
        const markAllReadBtn = document.getElementById('markAllReadBtn');

        if (notificationToggle && notificationDropdown) {
            // Toggle notification dropdown
            notificationToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isVisible = notificationDropdown.style.display === 'block';
                notificationDropdown.style.display = isVisible ? 'none' : 'block';
                
                if (!isVisible) {
                    loadNotifications();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!notificationToggle.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.style.display = 'none';
                }
            });

            // Mark all as read functionality
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    markAllAsRead();
                });
            }

            // Load initial notification count
            loadNotificationCount();

            // Refresh notification count every 30 seconds
            setInterval(loadNotificationCount, 30000);
        }

        // Load notification count
        function loadNotificationCount() {
            const gymSlug = getGymSlug();

            if (!gymSlug) return;
            
            fetch(`/auth/gym/${gymSlug}/notifications/unread-count`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const count = data.data.count;
                    updateNotificationBadge(count);
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
        }

        // Load notifications for dropdown
        function loadNotifications() {
            const gymSlug = getGymSlug();
            if (!gymSlug) return;

            fetch(`/auth/gym/${gymSlug}/notifications/recent`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayNotifications(data.data.notifications);
                } else {
                    notificationList.innerHTML = '<div class="notification-error">Error loading notifications</div>';
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = '<div class="notification-error">Error loading notifications</div>';
            });
        }

        function displayNotifications(notifications) 
        {
            if (notifications.length === 0) {
                notificationList.innerHTML = '<div class="notification-empty">No notifications</div>';
                return;
            }

            const notificationsHtml = notifications.map(notification => {
                const isRead = notification.read_at !== null;
                const timeAgo = getTimeAgo(notification.created_at);
                const priorityClass = notification.priority || 'normal';
                
                return `
                    <div class="notification-item ${isRead ? 'read' : 'unread'}" data-id="${notification.id}">
                        <div class="notification-content">
                            <div class="notification-title">${notification.data.title || 'Notification'}</div>
                            <div class="notification-message">${notification.data.message || notification.data.body || ''}</div>
                            <div class="notification-time">${timeAgo}</div>
                        </div>
                        ${!isRead ? '<button class="mark-read-btn" onclick="markAsRead(' + notification.id + ')">×</button>' : ''}
                    </div>
                `;
            }).join('');

            notificationList.innerHTML = notificationsHtml;
        }

        // Mark notification as read
        window.markAsRead = function(notificationId) {
            const gymSlug = getGymSlug();
            if (!gymSlug) return;
            
            fetch(`/auth/gym/${gymSlug}/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the mark as read button and add read class
                    const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                        notificationItem.classList.add('read');
                        const markReadBtn = notificationItem.querySelector('.mark-read-btn');
                        if (markReadBtn) {
                            markReadBtn.remove();
                        }
                    }
                    // Refresh notification count
                    loadNotificationCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        };

        // Mark all notifications as read
        function markAllAsRead() {
            const gymSlug = getGymSlug();
            if (!gymSlug) return;
            
            fetch(`/auth/gym/${gymSlug}/notifications/mark-all-read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update all notification items to read state
                    const notificationItems = document.querySelectorAll('.notification-item');
                    notificationItems.forEach(item => {
                        item.classList.remove('unread');
                        item.classList.add('read');
                        const markReadBtn = item.querySelector('.mark-read-btn');
                        if (markReadBtn) {
                            markReadBtn.remove();
                        }
                    });
                    // Update badge
                    updateNotificationBadge(0);
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }

        // Update notification badge
        function updateNotificationBadge(count) {
            if (notificationBadge) {
                if (count > 0) {
                    notificationBadge.textContent = count > 99 ? '99+' : count;
                    notificationBadge.style.display = 'inline-block';
                } else {
                    notificationBadge.style.display = 'none';
                }
            }
            
            if (notificationBadgeMobile) {
                if (count > 0) {
                    notificationBadgeMobile.textContent = count > 99 ? '99+' : count;
                    notificationBadgeMobile.style.display = 'inline-block';
                } else {
                    notificationBadgeMobile.style.display = 'none';
                }
            }
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
            if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + 'd ago';
            return date.toLocaleDateString();
        }

        function getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            return token || '';
        }

        function getGymSlug() {
            const gymContextElement = document.querySelector('[data-gym-context]');
            if (gymContextElement) {
                const gymContext = JSON.parse(gymContextElement.dataset.gymContext);
                return gymContext.slug;
            }
            
            const pathParts = window.location.pathname.split('/');
            const gymIndex = pathParts.indexOf('gym');
            if (gymIndex !== -1 && pathParts[gymIndex + 1]) {
                return pathParts[gymIndex + 1];
            }
            
            return null;
        }
    });
</script>