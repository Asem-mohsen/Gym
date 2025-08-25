<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('{{ $formId }}');
        const submitButton = document.getElementById('{{ $submitButtonId }}');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;
            
            // Get form data
            const formData = new FormData(form);
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.status === 429) {
                    // Handle throttle exceeded
                    return response.json().then(data => {
                        throw new Error(data.message || 'Too many login attempts. Please wait a moment before trying again.');
                    });
                }
                
                if (!response.ok) {
                    // Handle other errors
                    return response.json().then(data => {
                        throw new Error(data.message || 'Invalid credentials. Please try again.');
                    });
                }
                
                return response.json();
            })
            .then(data => {
                // Success - redirect to home page
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    toastr.success('{{ $successMessage }}');
                    setTimeout(() => {
                        window.location.href = form.getAttribute('data-kt-redirect-url');
                    }, 1000);
                }
            })
            .catch(error => {
                // Show error message
                toastr.error(error.message);
                
                // Reset button state
                submitButton.removeAttribute('data-kt-indicator');
                submitButton.disabled = false;
            });
        });
    });
    </script>