@push('scripts')
<script>
    class GymContextHandler {
        constructor() {
            this.storageKey = 'current_gym_context';
            this.init();
        }

        init() {
            this.storeCurrentGymContext();
            
            this.listenForRouteChanges();
        }

        storeCurrentGymContext() {
            const gymContextElement = document.querySelector('[data-gym-context]');
            if (gymContextElement) {
                const gymContext = JSON.parse(gymContextElement.dataset.gymContext);
                this.setGymContext(gymContext);
            }
        }

        setGymContext(gymContext) {
            localStorage.setItem(this.storageKey, JSON.stringify(gymContext));
            
            sessionStorage.setItem(this.storageKey, JSON.stringify(gymContext));
            
            window.dispatchEvent(new CustomEvent('gymContextChanged', { 
                detail: gymContext 
            }));
        }

        getGymContext() {
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
        }

        updateGymContext(newGymContext) {
            this.clearGymContext();
            this.setGymContext(newGymContext);
        }

        listenForRouteChanges() {
            window.addEventListener('popstate', () => {
                setTimeout(() => this.storeCurrentGymContext(), 100);
            });
            
            window.addEventListener('gymRouteChanged', () => {
                setTimeout(() => this.storeCurrentGymContext(), 100);
            });
        }

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

        validateGymContext(expectedGymId) {
            const currentContext = this.getGymContext();
            return currentContext && currentContext.id == expectedGymId;
        }
    }

    window.gymContextHandler = new GymContextHandler();

    window.GymContextHandler = GymContextHandler;
</script>
@endpush
