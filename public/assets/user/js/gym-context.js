/**
 * Gym Context Manager for Mobile and Web Applications
 * This utility helps manage gym context across different gym routes
 */

class GymContextManager {
    constructor() {
        this.storageKey = 'current_gym_context';
        this.apiBaseUrl = '/api/v1';
        this.init();
    }

    init() {
        // Check if we're on a gym route and store context
        this.detectAndStoreGymContext();
        
        // Listen for route changes
        this.listenForRouteChanges();
    }

    /**
     * Detect gym context from current page and store it
     */
    detectAndStoreGymContext() {
        // Check if we're on a gym route by looking for gym context data
        const gymContextElement = document.querySelector('[data-gym-context]');
        if (gymContextElement) {
            try {
                const gymContext = JSON.parse(gymContextElement.dataset.gymContext);
                this.setGymContext(gymContext);
            } catch (e) {
                console.error('Error parsing gym context:', e);
            }
        }
    }

    /**
     * Set gym context in localStorage and sessionStorage
     */
    setGymContext(gymContext) {
        // Store in localStorage for persistence
        localStorage.setItem(this.storageKey, JSON.stringify(gymContext));
        
        // Store in sessionStorage for current session
        sessionStorage.setItem(this.storageKey, JSON.stringify(gymContext));
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('gymContextChanged', { 
            detail: gymContext 
        }));
        
        console.log('Gym context stored:', gymContext);
        
        // Also update via API if available
        this.updateGymContextViaAPI(gymContext);
    }

    /**
     * Get current gym context
     */
    getGymContext() {
        // Try sessionStorage first, then localStorage
        const sessionContext = sessionStorage.getItem(this.storageKey);
        if (sessionContext) {
            return JSON.parse(sessionContext);
        }
        
        const localContext = localStorage.getItem(this.storageKey);
        if (localContext) {
            return JSON.parse(localContext);
        }
        
        return null;
    }

    /**
     * Clear current gym context
     */
    clearGymContext() {
        localStorage.removeItem(this.storageKey);
        sessionStorage.removeItem(this.storageKey);
        
        window.dispatchEvent(new CustomEvent('gymContextCleared'));
        
        console.log('Gym context cleared');
        
        // Also clear via API if available
        this.clearGymContextViaAPI();
    }

    /**
     * Update gym context (clear old and set new)
     */
    updateGymContext(newGymContext) {
        this.clearGymContext();
        this.setGymContext(newGymContext);
    }

    /**
     * Validate if user is in correct gym context
     */
    validateGymContext(expectedGymId) {
        const currentContext = this.getGymContext();
        return currentContext && currentContext.id == expectedGymId;
    }

    /**
     * Get gym context for forms
     */
    getGymContextForForm() {
        const context = this.getGymContext();
        if (context) {
            return {
                id: context.id,
                slug: context.slug,
                name: context.name,
                logo: context.logo
            };
        }
        return null;
    }

    /**
     * Listen for route changes
     */
    listenForRouteChanges() {
        // For SPA-like applications, listen for navigation events
        window.addEventListener('popstate', () => {
            setTimeout(() => this.detectAndStoreGymContext(), 100);
        });
        
        // Listen for custom navigation events
        window.addEventListener('gymRouteChanged', () => {
            setTimeout(() => this.detectAndStoreGymContext(), 100);
        });
    }

    /**
     * Update gym context via API
     */
    async updateGymContextViaAPI(gymContext) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/gym-context`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({
                    gym_id: gymContext.id,
                    gym_slug: gymContext.slug,
                    gym_name: gymContext.name,
                    gym_logo: gymContext.logo
                })
            });

            if (response.ok) {
                console.log('Gym context updated via API');
            }
        } catch (error) {
            console.error('Error updating gym context via API:', error);
        }
    }

    /**
     * Clear gym context via API
     */
    async clearGymContextViaAPI() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/gym-context`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            if (response.ok) {
                console.log('Gym context cleared via API');
            }
        } catch (error) {
            console.error('Error clearing gym context via API:', error);
        }
    }

    /**
     * Get CSRF token from meta tag
     */
    getCSRFToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }

    /**
     * Check if gym context is valid for a specific route
     */
    isGymContextValidForRoute(routeGymId) {
        const currentContext = this.getGymContext();
        if (!currentContext) {
            return false;
        }
        
        return currentContext.id == routeGymId;
    }

    /**
     * Redirect to gym selection if context is invalid
     */
    redirectToGymSelectionIfInvalid(routeGymId, redirectUrl = '/') {
        if (!this.isGymContextValidForRoute(routeGymId)) {
            this.clearGymContext();
            window.location.href = redirectUrl;
            return false;
        }
        return true;
    }
}

// Initialize the gym context manager
window.gymContextManager = new GymContextManager();

// Make it available globally
window.GymContextManager = GymContextManager;

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GymContextManager;
}
