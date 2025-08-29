@extends('layout.admin.master')

@section('title', 'Import History')

@section('main-breadcrumb', 'Import')
@section('main-breadcrumb-link', route('admin.import.index'))

@section('sub-breadcrumb', 'History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Import History</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.import.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-upload"></i> New Import
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Import History</h5>
                        <p>This feature will show the history of all import operations. Currently, this is a placeholder view.</p>
                        <p>Future enhancements will include:</p>
                        <ul>
                            <li>List of all import operations</li>
                            <li>Success/failure rates</li>
                            <li>Download of import logs</li>
                            <li>Detailed error reports</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
