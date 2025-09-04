<script>
    // Phone number formatting
    document.getElementById('invitee_phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            value = '+' + value;
        }
        e.target.value = value;
    });

    // Function to open invitation modal
    function openInvitationModal() {
        // Try Bootstrap 5 first
        if (typeof bootstrap !== 'undefined') {
            var modal = new bootstrap.Modal(document.getElementById('invitationModal'));
            modal.show();
        }
        // Fallback to jQuery if available
        else if (typeof $ !== 'undefined') {
            $('#invitationModal').modal('show');
        }
        // Fallback to vanilla JS
        else {
            var modal = document.getElementById('invitationModal');
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Add backdrop
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }

    // Tab navigation function
    function switchTab(tabName) {
        // Hide all tab panes
        var tabPanes = document.querySelectorAll('.tab-pane');
        tabPanes.forEach(function(pane) {
            pane.classList.remove('show', 'active');
        });
        
        // Remove active class from all nav links
        var navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(function(link) {
            link.classList.remove('active');
        });
        
        // Show selected tab pane
        var selectedPane = document.getElementById(tabName);
        if (selectedPane) {
            selectedPane.classList.add('show', 'active');
        }
        
        // Add active class to clicked nav link
        var activeLink = document.querySelector('[data-bs-target="#' + tabName + '"]');
        if (activeLink) {
            activeLink.classList.add('active');
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tab navigation
        var tabLinks = document.querySelectorAll('.nav-link');
        tabLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var target = this.getAttribute('data-bs-target');
                if (target) {
                    var tabName = target.replace('#', '');
                    switchTab(tabName);
                }
            });
        });

        // Close modal when clicking on backdrop or close button
        var modal = document.getElementById('invitationModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            
            var closeButtons = modal.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
            closeButtons.forEach(function(button) {
                button.addEventListener('click', closeModal);
            });
        }
    });

    function closeModal() {
        var modal = document.getElementById('invitationModal');
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        // Remove backdrop
        var backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }

    // Function to resend invitation
    function resendInvitation(button) {
        var action = button.dataset.url;
        if (!action) {
            console.error('No action URL found on button');
            return;
        }

        // Create and submit a POST form (works without AJAX)
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = action;

        // CSRF token (works even if this JS is in an external file)
        var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = token;
        form.appendChild(csrfInput);

        // Optionally disable the button to avoid double submit
        button.disabled = true;

        document.body.appendChild(form);
        form.submit();
    }
</script>