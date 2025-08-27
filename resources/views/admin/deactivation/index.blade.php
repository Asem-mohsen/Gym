@extends('layout.admin.master')

@section('title', 'Gym Deactivation Management')

@section('css')
    @include('admin.deactivation.assets.style')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gym Deactivation Management</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Warning</h5>
                            <p>This section allows you to deactivate gyms and branches. Please be extremely careful as these actions are irreversible and will affect all associated data.</p>
                        </div>

                        <!-- Gym Deactivation Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header align-items-center">
                                        <h4>Gym Deactivation</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="gymDeactivationForm">
                                            <div class="form-group">
                                                <label for="gymSelect" class="form-label">Your Gym:</label>
                                                <input type="text" class="form-control form-control-solid mb-3" value="{{ $gym->gym_name ?? 'Gym #' . $gym->id }}" readonly>
                                                <input type="hidden" id="gymSelect" value="{{ $gym->id }}">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                <button type="button" class="btn btn-warning" id="previewGymBtn">
                                                    <i class="fas fa-eye"></i> Preview Data
                                                </button>
                                                <button type="button" class="btn btn-danger" id="deactivateGymBtn">
                                                    <i class="fas fa-trash"></i> Deactivate Gym
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Branch Deactivation Section -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header align-items-center">
                                        <h4>Branch Deactivation</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="branchDeactivationForm">
                                            <div class="form-group">
                                                <label for="branchSelect" class="form-label">Select Branch to Deactivate:</label>
                                                <select class="form-control form-control-solid mb-3" id="branchSelect" name="branch_id">
                                                    <option value="">Choose a branch...</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}">{{ $branch->name ?? 'Branch #' . $branch->id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                <button type="button" class="btn btn-warning" id="previewBranchBtn">
                                                    <i class="fas fa-eye"></i> Preview Data
                                                </button>
                                                <button type="button" class="btn btn-danger" id="deactivateBranchBtn" disabled>
                                                    <i class="fas fa-trash"></i> Deactivate Branch
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Modal -->
                        <div class="modal fade" id="previewModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Data Preview</h5>
                                        <button type="button" class="btn btn-outline-dark close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="previewContent">
                                        <!-- Content will be loaded here -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Modal -->
                        <div class="modal fade" id="confirmationModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Deactivation</h5>
                                        <button type="button" class="btn btn-outline-dark close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger">
                                            <h6><i class="fas fa-exclamation-triangle"></i> Final Warning</h6>
                                            <p>Are you absolutely sure you want to deactivate this item? This action cannot be undone and will affect all associated data.</p>
                                            <p><strong>Type "CONFIRM" to proceed:</strong></p>
                                            <input type="text" class="form-control" id="confirmText" placeholder="Type CONFIRM">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        @can('deactivate_gyms_and_branches')
                                            <button type="button" class="btn btn-danger" id="confirmDeactivationBtn" disabled>
                                                <i class="fas fa-trash"></i> Deactivate
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">
                            <i class="fas fa-info-circle me-2 text-white"></i> Instructions & Policy
                        </h4>
                    </div>
                    <div class="card-body p-4 d-flex flex-column gap-5">
                        <div class="mb-4">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-download me-2"></i> Policy Documents
                            </h5>
                            @if($policyDocuments->count() > 0)
                                <div class="d-grid gap-2">
                                    @foreach($policyDocuments as $document)
                                        <a href="{{ route('admin.resources.download', $document->id) }}" 
                                        class="btn btn-outline-secondary btn-sm policy-doc-btn text-start"
                                        target="_blank">
                                        <div class="symbol symbol-60px mb-5">
                                                <img src="{{ asset('assets/admin/img/files/'.getDocumentExtension($document).'.svg') }}" class="theme-light-show" alt="" />
                                            </div>
                                            <span >{{ $document->title }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info border-0">
                                    <i class="fas fa-info-circle me-2"></i> No policy documents available.
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-list-check me-2"></i> Deactivation Process
                            </h5>
                            <div class="accordion shadow-sm" id="deactivationAccordion">
                                <div class="accordion-item border-0 mb-2">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#branchProcess">
                                            <i class="fas fa-building me-2"></i> Branch Deactivation
                                        </button>
                                    </h2>
                                    <div id="branchProcess" class="accordion-collapse collapse" data-bs-parent="#deactivationAccordion">
                                        <div class="accordion-body bg-white">
                                            <ol class="mb-0 ps-3">
                                                <li class="mb-1">Soft delete the branch</li>
                                                <li class="mb-1">Deactivate all users in that branch</li>
                                                <li class="mb-1">Soft delete associated services</li>
                                                <li class="mb-1">Soft delete associated classes</li>
                                                <li class="mb-1">Soft delete invitations sent by branch users</li>
                                                <li class="mb-1">Soft delete all related data (phones, galleries, etc.)</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#gymProcess">
                                            <i class="fas fa-dumbbell me-2"></i> Gym Deactivation
                                        </button>
                                    </h2>
                                    <div id="gymProcess" class="accordion-collapse collapse" data-bs-parent="#deactivationAccordion">
                                        <div class="accordion-body bg-white">
                                            <ol class="mb-0 ps-3">
                                                <li class="mb-1"><strong>Immediate:</strong> Send data export email to gym owner</li>
                                                <li class="mb-1"><strong>2 Days:</strong> Soft delete all accounts and data</li>
                                                <li class="mb-1"><strong>30 Days:</strong> Permanently delete all data</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-shield-alt me-2"></i> Security Measures
                            </h5>
                            <ul class="list-unstyled">
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i> Admin role required to deactivate
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i> Gym isolation (own gym only)
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i> Double confirmation required
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i> Data export before deletion
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i> Grace period for recovery
                                </li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i> Important Notes
                            </h5>
                            <div class="alert alert-info border-0 shadow-sm">
                                <ul class="mb-0 ps-3">
                                    <li class="mb-1">Data export includes all gym information in Excel format</li>
                                    <li class="mb-1">Soft deletion allows for potential recovery</li>
                                    <li class="mb-1">Permanent deletion is irreversible</li>
                                    <li class="mb-1">Email notifications are sent to gym owner</li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <h5 class="text-primary fw-bold mb-3">
                                <i class="fas fa-headset me-2"></i> Support
                            </h5>
                            <p class="mb-3 text-muted">If you need assistance or have questions about the deactivation process, please contact:</p>
                            <ul class="list-unstyled">
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-envelope text-primary me-2"></i> support@gymsystem.com
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="fas fa-phone text-primary me-2"></i> +1-800-GYM-HELP
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('admin.deactivation.assets.script')
@endsection
