@push('scripts')
<script>
class GymContextHandler {
    constructor() {
        this.storageKey = 'current_gym_context';
        this.init();
    }

    init() {
        // Check if we're on a gym route and store context
        this.storeCurrentGymContext();
        
        // Listen for route changes (for SPA-like behavior)
        this.listenForRouteChanges();
    }

    storeCurrentGymContext() {
        // Get gym context from the page (set by middleware)
        const gymContextElement = document.querySelector('[data-gym-context]');
        if (gymContextElement) {
            const gymContext = JSON.parse(gymContextElement.dataset.gymContext);
            this.setGymContext(gymContext);
        }
    }

    setGymContext(gymContext) {
        // Store in localStorage
        localStorage.setItem(this.storageKey, JSON.stringify(gymContext));
        
        // Store in sessionStorage for current session
        sessionStorage.setItem(this.storageKey, JSON.stringify(gymContext));
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('gymContextChanged', { 
            detail: gymContext 
        }));
        
        console.log('Gym context stored:', gymContext);
    }

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

    clearGymContext() {
        localStorage.removeItem(this.storageKey);
        sessionStorage.removeItem(this.storageKey);
        
        window.dispatchEvent(new CustomEvent('gymContextCleared'));
        
        console.log('Gym context cleared');
    }

    updateGymContext(newGymContext) {
        // Clear old context and set new one
        this.clearGymContext();
        this.setGymContext(newGymContext);
    }

    listenForRouteChanges() {
        // For SPA-like applications, listen for navigation events
        window.addEventListener('popstate', () => {
            setTimeout(() => this.storeCurrentGymContext(), 100);
        });
        
        // Listen for custom navigation events
        window.addEventListener('gymRouteChanged', () => {
            setTimeout(() => this.storeCurrentGymContext(), 100);
        });
    }

    // Method to get gym context for forms
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

    // Method to validate if user is in correct gym context
    validateGymContext(expectedGymId) {
        const currentContext = this.getGymContext();
        return currentContext && currentContext.id == expectedGymId;
    }
}

// Initialize the gym context handler
window.gymContextHandler = new GymContextHandler();

// Make it available globally
window.GymContextHandler = GymContextHandler;
</script>
@endpush
