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

// Image preview functionality
function initImagePreview() {
    // Main image preview
    $('#main_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#main_image_preview_img').attr('src', e.target.result);
                $('#main_image_preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#main_image_preview').hide();
        }
    });

    // Gallery images preview
    $('#gallery_images').on('change', function(e) {
        const files = e.target.files;
        const previewContainer = $('#gallery_preview_container');
        const previewDiv = $('#gallery_images_preview');
        
        // Clear previous previews
        previewContainer.empty();
        
        if (files.length > 0) {
            previewDiv.show();
            
            Array.from(files).forEach(function(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewHtml = `
                        <div class="col-md-3 mb-3">
                            <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                        </div>
                    `;
                    previewContainer.append(previewHtml);
                };
                reader.readAsDataURL(file);
            });
        } else {
            previewDiv.hide();
        }
    });
}

// Opening hours functionality
function initOpeningHours() {
    // Handle closed toggle
    $(document).on('change', '.closed-toggle', function() {
        const timeInputs = $(this).closest('.card-body').find('.time-inputs');
        const isClosed = $(this).is(':checked');
        
        if (isClosed) {
            timeInputs.find('input[type="time"]').prop('disabled', true).val('').removeAttr('required');
            $(this).val('1');
            // Remove any existing hidden input
            $(this).siblings('input[type="hidden"]').remove();
        } else {
            timeInputs.find('input[type="time"]').prop('disabled', false).attr('required', 'required');
            // Remove the value attribute so it doesn't send anything when unchecked
            $(this).removeAttr('value');
        }
    });
    
    // Initialize existing checkboxes
    $('.closed-toggle').each(function() {
        const timeInputs = $(this).closest('.card-body').find('.time-inputs');
        const isClosed = $(this).is(':checked');
        
        if (isClosed) {
            timeInputs.find('input[type="time"]').prop('disabled', true).removeAttr('required');
            $(this).val('1');
        } else {
            timeInputs.find('input[type="time"]').prop('disabled', false).attr('required', 'required');
            // Remove the value attribute so it doesn't send anything when unchecked
            $(this).removeAttr('value');
        }
    });
}

$(document).ready(function() {
    // Initialize opening hours repeater
    $('#opening-hours-repeater').repeater({
        initEmpty: false,
        show: function() {
            $(this).slideDown();
            fixRepeaterIds();
        },
        hide: function(deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });
    
    initOpeningHours();
    initDayConflictDetection();
});

function fixRepeaterIds() {
    $('#opening-hours-repeater [data-repeater-item]').each(function(index) {
        const $item = $(this);
        
        // Fix day checkboxes
        $item.find('.day-checkbox').each(function() {
            const $checkbox = $(this);
            const dayValue = $checkbox.val();
            const newId = `day_${dayValue}_${index}`;
            $checkbox.attr('id', newId);
            $checkbox.attr('name', `opening_hours[${index}][days][]`);
            $checkbox.next('label').attr('for', newId);
        });
        
        // Fix closed toggle
        const $closedToggle = $item.find('.closed-toggle');
        
        // Remove any duplicate closed-toggle elements to prevent array issues
        $item.find('.closed-toggle').not(':first').remove();
        const $singleClosedToggle = $item.find('.closed-toggle').first();
        
        $singleClosedToggle.attr('id', `closed_${index}`);
        $singleClosedToggle.attr('name', `opening_hours[${index}][is_closed]`);
        $singleClosedToggle.next('label').attr('for', `closed_${index}`);
        
        // Initialize closed toggle value
        if ($singleClosedToggle.is(':checked')) {
            $singleClosedToggle.val('1');
        } else {
            $singleClosedToggle.removeAttr('value');
        }
        
        // Fix time inputs
        $item.find('input[name*="opening_time"]').attr('name', `opening_hours[${index}][opening_time]`);
        $item.find('input[name*="closing_time"]').attr('name', `opening_hours[${index}][closing_time]`);
    });
    
    // Update day conflicts after fixing IDs
    updateDayConflicts();
}

function updateDayConflicts() {
    const selectedDays = new Set();
    
    // Collect all selected days from existing time slots
    $('#opening-hours-repeater [data-repeater-item]').each(function() {
        const $item = $(this);
        $item.find('.day-checkbox:checked').each(function() {
            selectedDays.add($(this).val());
        });
    });
    
    // Disable already selected days in all time slots
    $('#opening-hours-repeater [data-repeater-item]').each(function() {
        const $item = $(this);
        $item.find('.day-checkbox').each(function() {
            const $checkbox = $(this);
            const dayValue = $checkbox.val();
            const isChecked = $checkbox.is(':checked');
            
            if (selectedDays.has(dayValue) && !isChecked) {
                $checkbox.prop('disabled', true);
                $checkbox.next('label').addClass('text-muted');
            } else {
                $checkbox.prop('disabled', false);
                $checkbox.next('label').removeClass('text-muted');
            }
        });
    });
}

function initDayConflictDetection() {
    $(document).on('change', '.day-checkbox', function() {
        updateDayConflicts();
    });
}
</script>
