@extends('layout.admin.master')

@section('title', 'Notification Management')

@section('main-breadcrumb', 'Notifications')
@section('main-breadcrumb-link', route('admin.notifications.index'))

@section('sub-breadcrumb', 'Index')

@section('content')

<div class="row">
    <!-- Recent Sent Notifications by Type -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-dark">Recent Sent Notifications</h6>
                <small class="text-muted">One from each type</small>
            </div>
            <div class="card-body" id="recent-sent-notifications">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent System Notifications -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-dark">Recent System Notifications</h6>
                <small class="text-muted">From users to admins</small>
            </div>
            <div class="card-body" id="recent-system-notifications">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark align-content-center">Quick Notification Templates</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <h6 class="card-title text-success">Gym Holiday Notice</h6>
                                <p class="card-text">Quick template for announcing gym holidays or closures.</p>
                                <button class="btn btn-sm btn-outline-success" onclick="useTemplate('holiday')">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-info">
                            <div class="card-body">
                                <h6 class="card-title text-info">Maintenance Notice</h6>
                                <p class="card-text">Template for equipment maintenance or facility updates.</p>
                                <button class="btn btn-sm btn-outline-info" onclick="useTemplate('maintenance')">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <h6 class="card-title text-warning">Special Offer</h6>
                                <p class="card-text">Template for promotions, discounts, or special events.</p>
                                <button class="btn btn-sm btn-outline-warning" onclick="useTemplate('offer')">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-dark">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row flex-column text-center">
                    @can('create_notifications')
                        <div class="col-md-12 mb-3">
                            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus mr-2"></i>Send New Notification
                            </a>
                        </div>
                    @endcan
                    @can('view_notifications')
                        <div class="col-md-12 mb-3">
                            <button class="btn btn-success btn-block" onclick="markAllAsRead()">
                                <i class="fas fa-check-double mr-2"></i>Mark All as Read
                            </button>
                        </div>
                        <div class="col-md-12 mb-3">
                            <a href="{{ route('admin.notifications.history') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-history mr-2"></i>View History
                            </a>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('admin.notifications.assets.scripts-index')
@endsection
