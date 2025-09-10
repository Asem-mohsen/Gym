<script>
// Centralized coordinate extraction function for branch forms
function extractCoordinatesFromUrl(url) {
    let lat, lng;
    
    // Format 1: https://maps.google.com/maps?q=30.0444,31.2357
    if (url.includes('q=')) {
        const match = url.match(/q=([0-9.-]+),([0-9.-]+)/);
        if (match) {
            lat = parseFloat(match[1]);
            lng = parseFloat(match[2]);
        }
    }
    
    // Format 2: https://www.google.com/maps/@30.0444,31.2357,15z
    if (!lat && url.includes('@')) {
        const match = url.match(/@([0-9.-]+),([0-9.-]+)/);
        if (match) {
            lat = parseFloat(match[1]);
            lng = parseFloat(match[2]);
        }
    }
    
    // Format 3: https://maps.google.com/maps/place/.../@30.0444,31.2357,15z
    if (!lat && url.includes('/place/') && url.includes('@')) {
        const match = url.match(/@([0-9.-]+),([0-9.-]+)/);
        if (match) {
            lat = parseFloat(match[1]);
            lng = parseFloat(match[2]);
        }
    }
    
    // Format 4: Extract from data parameter in the URL (most accurate for place URLs)
    if (!lat && url.includes('data=')) {
        // Try multiple patterns for different URL formats
        let dataMatch = url.match(/data=!3m2!1e3!4b1!4m6!3m5!1s[^!]*!8m2!3d([0-9.-]+)!4d([0-9.-]+)/);
        if (dataMatch) {
            lat = parseFloat(dataMatch[1]);
            lng = parseFloat(dataMatch[2]);
        } else {
            // Try another pattern for different URL formats
            dataMatch = url.match(/data=!3m1!1e3!4m4!3m3!8m2!3d([0-9.-]+)!4d([0-9.-]+)/);
            if (dataMatch) {
                lat = parseFloat(dataMatch[1]);
                lng = parseFloat(dataMatch[2]);
            }
        }
    }
    
    // Format 5: Extract from the URL path itself (for place URLs)
    if (!lat && url.includes('/place/')) {
        // Try to extract from the URL structure like /place/Name/@lat,lng,zoom
        const placeMatch = url.match(/\/place\/[^@]*@([0-9.-]+),([0-9.-]+)/);
        if (placeMatch) {
            lat = parseFloat(placeMatch[1]);
            lng = parseFloat(placeMatch[2]);
        }
    }
    
    return { lat, lng };
}

// Initialize coordinate extraction functionality
function initCoordinateExtraction() {
    $('#getCoordinatesBtn').click(function() {
        const mapUrl = $('#map_url').val();
        
        if (!mapUrl) {
            toastr.warning('Please enter a Google Maps URL first.');
            return;
        }
        
        const coords = extractCoordinatesFromUrl(mapUrl);
        
        if (coords.lat && coords.lng) {
            // Limit precision to 7 decimal places to avoid floating point issues
            $('#latitude').val(parseFloat(coords.lat.toFixed(7)));
            $('#longitude').val(parseFloat(coords.lng.toFixed(7)));
            
            // Show success message
            $(this).removeClass('btn-light-primary').addClass('btn-success');
            $(this).html('<i class="fas fa-check me-2"></i>Coordinates Extracted!');
            
            setTimeout(() => {
                $(this).removeClass('btn-success').addClass('btn-light-primary');
                $(this).html('<i class="fas fa-map-marker-alt me-2"></i>Get Coordinates from Map URL');
            }, 2000);
        } else {
            toastr.error('Could not extract coordinates from the URL. Please make sure it\'s a valid Google Maps URL with coordinates.');
        }
    });

    // Auto-fill city and region based on coordinates (basic implementation)
    $('#latitude, #longitude').on('blur', function() {
        const lat = parseFloat($('#latitude').val());
        const lng = parseFloat($('#longitude').val());
        
        if (lat && lng && !$('#city').val()) {
            // Basic city detection for Egypt
            if (lat >= 29.8 && lat <= 30.2 && lng >= 31.0 && lng <= 31.5) {
                $('#city').val('Cairo');
                $('#region').val('Cairo Governorate');
            } else if (lat >= 31.0 && lat <= 31.5 && lng >= 29.5 && lng <= 30.0) {
                $('#city').val('Alexandria');
                $('#region').val('Alexandria Governorate');
            } else if (lat >= 30.0 && lat <= 30.5 && lng >= 30.5 && lng <= 31.0) {
                $('#city').val('Giza');
                $('#region').val('Giza Governorate');
            }
        }
    });
}
</script>
