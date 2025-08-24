<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Search and Location Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('gymSearch');
        const gymItems = document.querySelectorAll('.gym-item');
        const searchResults = document.getElementById('searchResults');
        const resultsCount = document.getElementById('resultsCount');
        const loading = document.getElementById('loading');
        const locationButton = document.getElementById('locationButton');
        
        let searchTimeout;
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            clearTimeout(searchTimeout);
            
            if (searchTerm.length > 0) {
                loading.style.display = 'block';
                searchResults.style.display = 'none';
            }
            
            searchTimeout = setTimeout(() => {
                let visibleCount = 0;
                
                gymItems.forEach(item => {
                    const gymName = item.dataset.gymName;
                    if (gymName.includes(searchTerm)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                loading.style.display = 'none';
                
                if (searchTerm.length > 0) {
                    searchResults.style.display = 'block';
                    resultsCount.textContent = visibleCount;
                    
                    if (visibleCount === 0) {
                        resultsCount.innerHTML = '<span style="color: #ef4444;">No gyms found</span>';
                    }
                } else {
                    searchResults.style.display = 'none';
                }
            }, 300);
        });
        
        // Location functionality
        locationButton.addEventListener('click', function() {
            if (navigator.geolocation) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Getting location...';
                this.disabled = true;
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // Redirect with location parameters
                        const currentUrl = new URL(window.location);
                        currentUrl.searchParams.set('latitude', latitude);
                        currentUrl.searchParams.set('longitude', longitude);
                        window.location.href = currentUrl.toString();
                    },
                    function(error) {
                        locationButton.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i>Use My Location';
                        locationButton.disabled = false;
                        alert('Unable to get your location. Please try again or search manually.');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        });
        
        // Keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
            }
        });
    });
</script>