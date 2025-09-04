<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load template if available
        loadTemplate();
        
        // Set up character counter
        setupCharacterCounter();
        
        // Set up preview updates
        setupPreviewUpdates();
        
        // Set up form validation
        setupFormValidation();
    });
    
    function loadTemplate() {
        const template = sessionStorage.getItem('notificationTemplate');
        if (template) {
            try {
                const data = JSON.parse(template);
                document.getElementById('subject').value = data.subject || '';
                document.getElementById('message').value = data.message || '';
                sessionStorage.removeItem('notificationTemplate');
                updatePreview();
            } catch (e) {
                console.error('Error loading template:', e);
            }
        }
    }
    
    function setupCharacterCounter() {
        const messageField = document.getElementById('message');
        const charCount = document.getElementById('char-count');
        
        messageField.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length > 900) {
                charCount.classList.add('text-warning');
            } else {
                charCount.classList.remove('text-warning');
            }
            
            if (length > 950) {
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-danger');
            }
        });
    }
    
    function setupPreviewUpdates() {
        const fields = ['subject', 'message', 'priority', 'action_text', 'action_url'];
        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener('input', updatePreview);
            }
        });
        
        // Update preview when checkboxes change
        document.querySelectorAll('input[name="target_roles[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updatePreview();
                updateRoleCount();
            });
        });
        
        // Add click handlers for role cards
        document.querySelectorAll('.role-selection-card').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                const checkbox = this.querySelector('.role-checkbox');
                checkbox.checked = !checkbox.checked;
                updatePreview();
                updateRoleCount();
            });
        });
        
        // Initialize role count
        updateRoleCount();
    }
    
    function updatePreview() {
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        const priority = document.getElementById('priority').value;
        const actionText = document.getElementById('action_text').value;
        const actionUrl = document.getElementById('action_url').value;
        
        const selectedRoles = Array.from(document.querySelectorAll('input[name="target_roles[]"]:checked'))
            .map(cb => cb.value.replace('_', ' '));
        
        let previewHtml = '';
        
        if (subject || message) {
            previewHtml = `
                <div class="notification-preview-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0 font-weight-bold">${subject || 'Subject'}</h6>
                        <span class="badge badge-${getPriorityBadgeClass(priority)}">${priority.toUpperCase()}</span>
                    </div>
                    <p class="mb-2">${message || 'Message content will appear here'}</p>
                    ${selectedRoles.length > 0 ? `<small class="text-muted">Target: ${selectedRoles.join(', ')}</small>` : ''}
                    ${actionText && actionUrl ? `<div class="mt-2"><a href="${actionUrl}" class="btn btn-sm btn-primary">${actionText}</a></div>` : ''}
                </div>
            `;
        } else {
            previewHtml = '<div class="text-muted text-center">Fill in the form above to see a preview</div>';
        }
        
        document.getElementById('notification-preview').innerHTML = previewHtml;
    }
    
    function getPriorityBadgeClass(priority) {
        switch(priority) {
            case 'urgent': return 'danger';
            case 'high': return 'warning';
            case 'normal': return 'info';
            case 'low': return 'secondary';
            default: return 'info';
        }
    }
    
    
    function setupFormValidation() {
        const form = document.getElementById('notification-form');
        
        form.addEventListener('submit', function(e) {
            const selectedRoles = document.querySelectorAll('input[name="target_roles[]"]:checked');
            
            if (selectedRoles.length === 0) {
                e.preventDefault();
                toastr.error('Please select at least one target role.');
                return false;
            }
            
            // Validate action button - if text is provided, URL must also be provided
            const actionText = document.getElementById('action_text').value.trim();
            const actionUrl = document.getElementById('action_url').value.trim();
            
            if (actionText && !actionUrl) {
                e.preventDefault();
                toastr.error('Please provide a URL when adding an action button.');
                return false;
            }
            
            if (!actionText && actionUrl) {
                e.preventDefault();
                toastr.error('Please provide button text when adding an action URL.');
                return false;
            }
            
        });
    }
    
    function updateRoleCount() {
        const selectedRoles = document.querySelectorAll('input[name="target_roles[]"]:checked');
        const count = selectedRoles.length;
        const roleCountElement = document.getElementById('role-count');
        
        if (count === 0) {
            roleCountElement.textContent = 'Select roles to send notification to';
            roleCountElement.className = 'text-muted d-block';
        } else if (count === 1) {
            roleCountElement.textContent = '1 role selected';
            roleCountElement.className = 'text-primary d-block';
        } else {
            roleCountElement.textContent = `${count} roles selected`;
            roleCountElement.className = 'text-primary d-block';
        }
    }
    
    function selectAllRoles() {
        document.querySelectorAll('input[name="target_roles[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
        updatePreview();
        updateRoleCount();
    }
    
    function clearAllRoles() {
        document.querySelectorAll('input[name="target_roles[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        updatePreview();
        updateRoleCount();
    }
</script>