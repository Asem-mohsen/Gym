@extends('layout.admin.master')

@section('title' , 'Admins')

@section('css')
<style>
    .admin-selection-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    
    .admin-selection-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .admin-selection-card.border-primary {
        border-color: #0d6efd !important;
        background-color: #f8f9ff;
    }
    
    .admin-selection-card .fa-check-circle {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 1.2rem;
    }
    
    .admin-selection-card .card-body {
        position: relative;
    }
</style>
@endsection

@section('page-title', 'Admins')

@section('main-breadcrumb', 'Admins')
@section('main-breadcrumb-link', route('admins.index'))

@section('sub-breadcrumb', 'Index')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">

        <div class="card-header border-0 pt-6">

            <div class="card-title">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search" />
                    </div>
                </div>
            </div>

            @can('create_admins')
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                        <a href="{{ route('admins.create') }}" class="btn btn-primary"><i class="ki-duotone ki-plus fs-2"></i>Add admin</a>
                    </div>
                </div>
            @endcan

        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Admin</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @foreach ($admins as $key => $admin)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div>
                                        <img src="{{ $admin->user_image}}" class="avatar avatar-sm me-3" alt="user1">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{$admin->name}}</h6>
                                        <small class="text-muted">{{$admin->email}}</small>
                                        <small class="text-muted">{{$admin->phone}}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach($admin->roles as $role)
                                    <span class="badge bg-primary text-white">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($admin->status)
                                    <x-badge 
                                        :color="'success'" 
                                        content="Active"
                                    />
                                @else
                                    <x-badge 
                                        :color="'danger'" 
                                        content="Disactivated"
                                    />
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('edit_admins')
                                        <x-table-icon-link 
                                            :route="route('admins.edit',$admin->id)" 
                                            colorClass="primary"
                                            title="Edit"
                                            iconClasses="fa-solid fa-pen"
                                        />
                                    @endcan
                                    @can('view_admins')
                                        <x-table-icon-link 
                                            :route="route('admins.show',$admin->id)" 
                                            colorClass="success"
                                            title="View"
                                            iconClasses="fa-solid fa-eye"
                                        />
                                    @endcan
                                    @can('edit_admins')
                                        @if(!$admin->has_set_password)
                                            <form action="{{ route('admins.resend-onboarding-email', $admin->id) }}" method="post" style="display: inline;">
                                                @csrf
                                                <x-icon-button
                                                    colorClass="warning"
                                                    title="Resend Onboarding Email"
                                                    iconClasses="fa-solid fa-envelope"
                                                    onclick="return confirm('Are you sure you want to resend the onboarding email to {{ $admin->name }}?')"
                                                />
                                            </form>
                                        @endif
                                    @endcan
                                    @can('delete_admins')
                                        <button type="button" 
                                                class="btn btn-icon-danger btn-sm delete-admin-btn" 
                                                data-admin-id="{{ $admin->id }}"
                                                data-admin-name="{{ $admin->name }}"
                                                title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Manager Reassignment Modal -->
<div class="modal fade" id="managerReassignmentModal" tabindex="-1" aria-labelledby="managerReassignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="managerReassignmentModalLabel">Reassign Branch Manager</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> The admin <span id="adminNameToDelete"></span> is currently managing one or more branches. 
                    Please select a new manager to reassign the branches before deleting this admin.
                </div>
                
                <div class="form-group">
                    <label class="form-label">Select New Manager:</label>
                    <div id="availableAdminsContainer" class="row g-3">
                        <!-- Available admins will be populated here -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReassignmentBtn" disabled>
                    Delete Admin & Reassign Branches
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
    
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
@endsection