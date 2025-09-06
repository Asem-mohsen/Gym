<script>
    $(document).ready(function() {
        let currentAction = null;
        let currentId = null;
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        // Gym preview
        $('#previewGymBtn').click(function() {
            const gymId = $('#gymSelect').val();
            if (!gymId) {
                toastr.error('Please select a gym first.');
                return;
            }
    
            $.get(`/admin/deactivation/gym/preview`)
                .done(function(response) {
                    if (response.success) {
                        displayPreview('Gym', response.data);
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Error loading gym data.');
                    }
                });
        });
    
        // Branch preview
        $('#previewBranchBtn').click(function() {
            const branchId = $('#branchSelect').val();
            if (!branchId) {
                toastr.error('Please select a branch first.');
                return;
            }
    
            $.get(`/admin/deactivation/branch/${branchId}/preview`)
                .done(function(response) {
                    if (response.success) {
                        displayPreview('Branch', response.data);
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(function(xhr) {
                    console.error('Preview failed:', xhr);
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Error loading branch data.');
                    }
                });
        });
    
        // Gym deactivation
        $('#deactivateGymBtn').click(function() {
            const gymId = $('#gymSelect').val();
            if (!gymId) {
                toastr.error('Please select a gym first.');
                return;
            }
    
            console.log('Deactivating gym:', gymId);
            currentAction = 'gym';
            currentId = gymId;
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });
    
        // Branch deactivation
        $('#deactivateBranchBtn').click(function() {
            const branchId = $('#branchSelect').val();
            if (!branchId) {
                toastr.error('Please select a branch first.');
                return;
            }
            currentAction = 'branch';
            currentId = branchId;
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });
    
        $('#gymSelect').change(function() {
            $('#deactivateGymBtn').prop('disabled', !$(this).val());
        });
    
        $('#branchSelect').change(function() {
            $('#deactivateBranchBtn').prop('disabled', !$(this).val());
        });
    
        $('#confirmText').on('input', function() {
            $('#confirmDeactivationBtn').prop('disabled', $(this).val() !== 'CONFIRM');
        });
    
        $('#confirmDeactivationBtn').click(function() {
            if (currentAction === 'gym') {
                deactivateGym(currentId);
            } else if (currentAction === 'branch') {
                deactivateBranch(currentId);
            }
        });
    
        function displayPreview(type, data) {
            let content = `<h6>${type} Data Summary:</h6><ul>`;
            
            for (const [key, value] of Object.entries(data)) {
                if (key !== 'id') {
                    content += `<li><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value}</li>`;
                }
            }
            
            content += '</ul>';
            $('#previewContent').html(content);
            const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            previewModal.show();
        }
    
        function deactivateGym(gymId) {
            
            $.post(`/admin/deactivation/gym/${gymId}/deactivate`)
                .done(function(response) {
                    if (response.success) {
                        toastr.success('Gym deactivated successfully!');
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Error deactivating gym.');
                    }
                })
                .always(function() {
                    const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                    if (confirmationModal) {
                        confirmationModal.hide();
                    }
                    $('#confirmText').val('');
                    $('#confirmDeactivationBtn').prop('disabled', true);
                });
        }
    
        function deactivateBranch(branchId) {
            $.post(`/admin/deactivation/branch/${branchId}/deactivate`)
                .done(function(response) {
                    if (response.success) {
                        toastr.success('Branch deactivated successfully!');
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Error deactivating branch.');
                    }
                })
                .always(function() {
                    const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                    if (confirmationModal) {
                        confirmationModal.hide();
                    }
                    $('#confirmText').val('');
                    $('#confirmDeactivationBtn').prop('disabled', true);
                });
        }
    
    });
</script>