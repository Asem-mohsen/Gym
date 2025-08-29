@extends('layout.admin.master')

@section('main-breadcrumb', 'Import')
@section('main-breadcrumb-link', route('admin.import.index'))

@section('sub-breadcrumb', 'Import Data')

@section('content')
<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="card-title">Import Gym Data</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.import.template') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-download fs-2"></i> Download Template
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <form id="importForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-5">
                            <label for="import_file" class="form-label fw-semibold fs-6">Select Excel File</label>
                            <div class="position-relative">
                                <input type="file" class="form-control form-control-solid" id="import_file" name="import_file" accept=".xlsx,.xls" required>
                                <div class="form-text">File must be an Excel file (.xlsx or .xls) and less than 10MB</div>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <button type="submit" class="btn btn-primary" id="importBtn">
                                <i class="ki-duotone ki-upload fs-2"></i> Import Data
                            </button>
                            <button type="button" class="btn btn-secondary" id="cancelBtn" style="display: none;">
                                <i class="ki-duotone ki-cross fs-2"></i> Cancel
                            </button>
                        </div>
                    </form>

                            <div id="importProgress" style="display: none;" class="mb-5">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm me-3" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Importing data, please wait...</h6>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="importResults" style="display: none;" class="mb-5">
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="ki-duotone ki-check-circle fs-2hx text-success me-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-1">Import Completed Successfully</h5>
                                        <div id="resultsContent"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="importErrors" style="display: none;" class="mb-5">
                                <div class="alert alert-danger d-flex align-items-center">
                                    <i class="ki-duotone ki-cross-circle fs-2hx text-danger me-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <div class="d-flex flex-column">
                                        <h5 class="mb-1">Import Errors</h5>
                                        <div id="errorsContent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Import Template Structure</h5>
                                </div>
                                <div class="card-body">
                                    <div class="accordion" id="templateAccordion">
                                        @foreach($template as $sheetName => $columns)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}">
                                                    {{ $sheetName }} Sheet
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#templateAccordion">
                                                <div class="accordion-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-nowrap" style="width: 30%;">Column</th>
                                                                    <th style="width: 70%;">Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($columns as $column => $description)
                                                                <tr>
                                                                    <td class="text-nowrap">
                                                                        <code class="fs-7">{{ $column }}</code>
                                                                    </td>
                                                                    <td>
                                                                        <small class="text-muted">{{ $description }}</small>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const fileInput = $('#import_file')[0];
        
        if (!fileInput.files[0]) {
            alert('Please select a file to import');
            return;
        }
        
        // Show progress
        $('#importBtn').prop('disabled', true).html('<i class="ki-duotone ki-spinner fs-2"></i> Importing...');
        $('#cancelBtn').show();
        $('#importProgress').show();
        $('#importResults').hide();
        $('#importErrors').hide();
        
        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(function() {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            $('.progress-bar').css('width', progress + '%');
        }, 500);
        
        $.ajax({
            url: '{{ route("admin.import.process") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                clearInterval(progressInterval);
                $('.progress-bar').css('width', '100%');
                
                setTimeout(function() {
                    $('#importProgress').hide();
                    $('#importBtn').prop('disabled', false).html('<i class="ki-duotone ki-upload fs-2"></i> Import Data');
                    $('#cancelBtn').hide();
                    
                    if (response.success) {
                        displayResults(response.data);
                    } else {
                        displayErrors(response.message || 'Import failed');
                    }
                }, 1000);
            },
            error: function(xhr) {
                clearInterval(progressInterval);
                $('#importProgress').hide();
                $('#importBtn').prop('disabled', false).html('<i class="ki-duotone ki-upload fs-2"></i> Import Data');
                $('#cancelBtn').hide();
                
                let errorMessage = 'Import failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage += '<br><ul>';
                    xhr.responseJSON.errors.forEach(function(error) {
                        errorMessage += '<li>' + error + '</li>';
                    });
                    errorMessage += '</ul>';
                }
                
                displayErrors(errorMessage);
            }
        });
    });
    
    $('#cancelBtn').on('click', function() {
        // Reset form
        $('#importForm')[0].reset();
        $('#importProgress').hide();
        $('#importBtn').prop('disabled', false).html('<i class="ki-duotone ki-upload fs-2"></i> Import Data');
        $(this).hide();
        $('#importResults').hide();
        $('#importErrors').hide();
    });
    
    function displayResults(data) {
        let content = '<div class="row g-4">';
        
        // Summary
        if (data.summary) {
            content += '<div class="col-12">';
            content += '<div class="d-flex flex-wrap gap-3">';
            content += '<div class="badge badge-light-success fs-7 px-3 py-2">Total Imported: ' + data.summary.total_imported + '</div>';
            content += '<div class="badge badge-light-danger fs-7 px-3 py-2">Total Errors: ' + data.summary.total_errors + '</div>';
            content += '<div class="badge badge-light-primary fs-7 px-3 py-2">Success Rate: ' + data.summary.success_rate + '%</div>';
            content += '</div>';
            content += '<div class="text-muted fs-7 mt-2">Imported At: ' + data.summary.imported_at + '</div>';
            content += '</div>';
        }
        
        // Detailed results
        const types = ['users', 'branches', 'memberships', 'classes', 'services'];
        types.forEach(function(type) {
            if (data[type] && data[type].count > 0) {
                content += '<div class="col-md-6">';
                content += '<div class="card card-flush h-100">';
                content += '<div class="card-header">';
                content += '<h6 class="card-title">' + type.charAt(0).toUpperCase() + type.slice(1) + '</h6>';
                content += '</div>';
                content += '<div class="card-body">';
                content += '<div class="d-flex justify-content-between align-items-center">';
                content += '<span class="badge badge-light-success">Imported: ' + data[type].count + '</span>';
                content += '<span class="badge badge-light-danger">Errors: ' + (data[type].errors ? data[type].errors.length : 0) + '</span>';
                content += '</div>';
                content += '</div>';
                content += '</div>';
                content += '</div>';
            }
        });
        
        content += '</div>';
        
        $('#resultsContent').html(content);
        $('#importResults').show();
    }
    
    function displayErrors(message) {
        $('#errorsContent').html(message);
        $('#importErrors').show();
    }
});
</script>
@endpush

@push('styles')
<style>
.accordion-button:not(.collapsed) {
    background-color: var(--kt-gray-100);
    color: var(--kt-gray-700);
}

.accordion-button:focus {
    box-shadow: none;
    border-color: var(--kt-gray-200);
}

.accordion-button:hover {
    background-color: var(--kt-gray-50);
}

.table-responsive {
    border-radius: 0.475rem;
}

.fs-7 {
    font-size: 0.85rem !important;
}

.form-control-solid {
    background-color: var(--kt-gray-100);
    border-color: var(--kt-gray-200);
}

.form-control-solid:focus {
    background-color: var(--kt-gray-50);
    border-color: var(--kt-primary);
}

.card-flush {
    border: 1px solid var(--kt-gray-200);
    box-shadow: 0 0.1rem 1rem 0.25rem rgba(0, 0, 0, 0.05);
}

.badge-light-success {
    background-color: var(--kt-success-light);
    color: var(--kt-success);
}

.badge-light-danger {
    background-color: var(--kt-danger-light);
    color: var(--kt-danger);
}

.badge-light-primary {
    background-color: var(--kt-primary-light);
    color: var(--kt-primary);
}
</style>
@endpush
