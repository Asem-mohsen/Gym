<script>
    $(document).ready(function() {
        let selectedAdminId = null;
        let adminToDeleteId = null;
        
        // Handle delete admin button click
        $('.delete-admin-btn').click(function() {
            const adminId = $(this).data('admin-id');
            const adminName = $(this).data('admin-name');
            
            // Send AJAX request to check if admin is a manager
            $.ajax({
                url: `/admin/admins/${adminId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Admin is not a manager, can be deleted directly
                        toastr.success(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else if (response.is_manager) {
                        // Admin is a manager, show reassignment modal
                        adminToDeleteId = adminId;
                        $('#adminNameToDelete').text(adminName);
                        populateAvailableAdmins(response.available_admins);
                        $('#managerReassignmentModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while processing the request.');
                    }
                }
            });
        });
        
        // Populate available admins in the modal
        function populateAvailableAdmins(admins) {
            const container = $('#availableAdminsContainer');
            container.empty();
            
            if (admins.length === 0) {
                container.html('<div class="col-12"><div class="alert alert-warning">No other admins available for reassignment. Please create another admin first.</div></div>');
                $('#confirmReassignmentBtn').prop('disabled', true);
                return;
            }
            
            admins.forEach(function(admin) {
                const userImage = admin.user_image || '/assets/admin/img/boy-avatar.jpg';
                const adminCard = `
                    <div class="col-md-6">
                        <div class="admin-selection-card card h-100" data-admin-id="${admin.id}">
                            <div class="card-body text-center">
                                <img src="${userImage}" 
                                     class="rounded-circle mb-3" 
                                     width="60" 
                                     height="60" 
                                     alt="${admin.name}" 
                                     onerror="this.onerror=null; this.src='/assets/admin/img/boy-avatar.jpg';"
                                     onload="console.log('Image loaded successfully:', this.src)">
                                <h6 class="card-title">${admin.name}</h6>
                                <p class="card-text text-muted">${admin.email}</p>
                                <div class="selection-indicator">
                                    <i class="fas fa-check-circle text-primary" style="display: none;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(adminCard);
            });
        }
        
        // Handle admin selection
        $(document).on('click', '.admin-selection-card', function() {
            $('.admin-selection-card').removeClass('border-primary').addClass('border-light');
            $('.admin-selection-card .fa-check-circle').hide();
            
            $(this).removeClass('border-light').addClass('border-primary');
            $(this).find('.fa-check-circle').show();
            
            selectedAdminId = $(this).data('admin-id');
            $('#confirmReassignmentBtn').prop('disabled', false);
        });
        
        // Handle confirmation button click
        $('#confirmReassignmentBtn').click(function() {
            if (!selectedAdminId || !adminToDeleteId) {
                toastr.error('Please select a new manager first.');
                return;
            }
            
            $.ajax({
                url: `/admin/admins/${adminToDeleteId}/reassign-manager-and-delete`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    new_manager_id: selectedAdminId
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#managerReassignmentModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('An error occurred while processing the request.');
                    }
                }
            });
        });
        
        // Reset modal state when closed
        $('#managerReassignmentModal').on('hidden.bs.modal', function() {
            selectedAdminId = null;
            adminToDeleteId = null;
            $('#confirmReassignmentBtn').prop('disabled', true);
            $('.admin-selection-card').removeClass('border-primary').addClass('border-light');
            $('.admin-selection-card .fa-check-circle').hide();
        });
    });
</script>