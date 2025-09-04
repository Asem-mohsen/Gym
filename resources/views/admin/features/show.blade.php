@extends('layout.admin.master')

@section('title','Feature Details')

@section('main-breadcrumb', 'Features')
@section('main-breadcrumb-link', route('features.index'))

@section('sub-breadcrumb','Feature Details')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Feature Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Feature Name (English)</label>
                        <p class="form-control-static">{{ $feature->getTranslation('name', 'en') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Feature Name (Arabic)</label>
                        <p class="form-control-static">{{ $feature->getTranslation('name', 'ar') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Description (English)</label>
                        <p class="form-control-static">{{ $feature->getTranslation('description', 'en') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Description (Arabic)</label>
                        <p class="form-control-static">{{ $feature->getTranslation('description', 'ar') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Order</label>
                        <p class="form-control-static">{{ $feature->order }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Status</label>
                        <p class="form-control-static">
                            @if($feature->status == 1)
                                <x-badge 
                                    :color="'success'" 
                                    content="Active"
                                />
                            @else
                                <x-badge 
                                    :color="'danger'" 
                                    content="Inactive"
                                />
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Created At</label>
                        <p class="form-control-static">{{ $feature->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-5">
                        <label class="form-label fw-bold">Updated At</label>
                        <p class="form-control-static">{{ $feature->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @can('edit_features')
                <a href="{{ route('features.edit', $feature->id) }}" class="btn btn-primary">Edit</a>
            @endcan
            @can('view_features')
                <a href="{{ route('features.index') }}" class="btn btn-dark">Back</a>
            @endcan
        </div>
    </div>
</div>

@endsection 