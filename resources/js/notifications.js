// Real-time notification handler
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.maxNotifications = 5;
        this.init();
    }

    init() {
        this.createNotificationContainer();
        this.setupEchoListener();
    }

    createNotificationContainer() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }
    }

    setupEchoListener() {
        if (window.Echo) {
            window.Echo.channel('notifications')
                .listen('notification.sent', (e) => {
                    console.log('Received notification:', e);
                    this.showNotification(e.notification);
                });
        } else {
            console.warn('Laravel Echo not available');
        }
    }

    showNotification(notification) {
        const notificationElement = this.createNotificationElement(notification);
        const container = document.getElementById('notification-container');
        
        container.appendChild(notificationElement);
        this.notifications.push(notificationElement);

        // Remove oldest notification if we exceed max
        if (this.notifications.length > this.maxNotifications) {
            const oldest = this.notifications.shift();
            oldest.remove();
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            this.removeNotification(notificationElement);
        }, 5000);

        // Animate in
        setTimeout(() => {
            notificationElement.style.transform = 'translateX(0)';
            notificationElement.style.opacity = '1';
        }, 100);
    }

    createNotificationElement(notification) {
        const element = document.createElement('div');
        element.className = 'notification-item';
        element.style.cssText = `
            background: white;
            border-left: 4px solid ${this.getColorValue(notification.color)};
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 16px;
            margin-bottom: 8px;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        `;

        element.innerHTML = `
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="flex-shrink: 0;">
                    <div style="
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        background: ${this.getColorValue(notification.color)};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 12px;
                    ">
                        ${this.getIconSymbol(notification.icon)}
                    </div>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <h4 style="
                        margin: 0 0 4px 0;
                        font-size: 14px;
                        font-weight: 600;
                        color: #1f2937;
                    ">${notification.title}</h4>
                    <p style="
                        margin: 0;
                        font-size: 13px;
                        color: #6b7280;
                        line-height: 1.4;
                    ">${notification.message}</p>
                    ${notification.action_url && notification.action_text ? `
                        <a href="${notification.action_url}" style="
                            display: inline-block;
                            margin-top: 8px;
                            padding: 4px 8px;
                            background: ${this.getColorValue(notification.color)};
                            color: white;
                            text-decoration: none;
                            border-radius: 4px;
                            font-size: 12px;
                            font-weight: 500;
                        ">${notification.action_text}</a>
                    ` : ''}
                </div>
                <button onclick="notificationManager.removeNotification(this.parentElement.parentElement)" style="
                    background: none;
                    border: none;
                    color: #9ca3af;
                    cursor: pointer;
                    padding: 4px;
                    border-radius: 4px;
                    font-size: 16px;
                    line-height: 1;
                ">&times;</button>
            </div>
        `;

        // Click to dismiss
        element.addEventListener('click', (e) => {
            if (!e.target.closest('a') && !e.target.closest('button')) {
                this.removeNotification(element);
            }
        });

        return element;
    }

    removeNotification(element) {
        element.style.transform = 'translateX(100%)';
        element.style.opacity = '0';
        
        setTimeout(() => {
            element.remove();
            const index = this.notifications.indexOf(element);
            if (index > -1) {
                this.notifications.splice(index, 1);
            }
        }, 300);
    }

    getColorValue(color) {
        const colors = {
            red: '#ef4444',
            yellow: '#f59e0b',
            blue: '#3b82f6',
            green: '#10b981',
            gray: '#6b7280'
        };
        return colors[color] || colors.gray;
    }

    getIconSymbol(icon) {
        const icons = {
            'check-circle': '✓',
            'exclamation-circle': '!',
            'exclamation-triangle': '⚠',
            'information-circle': 'i',
            'calendar': '📅',
            'credit-card': '💳',
            'lock-closed': '🔒',
            'academic-cap': '🎓',
            'bell': '🔔'
        };
        return icons[icon] || icons.bell;
    }

    // Public method to manually show notifications (for testing)
    showTestNotification() {
        const notification = {
            id: 'test-' + Date.now(),
            type: 'info',
            title: 'Test Notification',
            message: 'This is a test notification to verify the system is working.',
            priority: 'normal',
            icon: 'bell',
            color: 'blue'
        };
        this.showNotification(notification);
    }
}

// Initialize notification manager
window.notificationManager = new NotificationManager();

// Export for use in other scripts
window.NotificationManager = NotificationManager;
