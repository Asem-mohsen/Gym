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
                                        <h5 class="mb-1" id="resultsTitle">Import Completed Successfully</h5>
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

@section('js')
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
            
            // Clear previous results
            $('#resultsContent').empty();
            $('#errorsContent').empty();
            
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
                        
                        console.log('Import response:', response);
                        
                        if (response.success) {
                            displayResults(response.data);
                            // If there are validation errors, also display them
                            if (response.errors && response.errors.length > 0) {
                                console.log('Validation errors found:', response.errors);
                                displayValidationErrors(response.errors);
                            }
                        } else {
                            displayErrors(response.message || 'Import failed');
                            // If there are validation errors, also display them
                            if (response.errors && response.errors.length > 0) {
                                console.log('Validation errors found:', response.errors);
                                displayValidationErrors(response.errors);
                            }
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
            // Set the title based on whether there are errors
            const hasErrors = data.summary && data.summary.total_errors > 0;
            const title = hasErrors ? 'Import Completed with Errors' : 'Import Completed Successfully';
            $('#resultsTitle').text(title);
            
            // Change alert class based on errors
            const alertDiv = $('#importResults .alert');
            if (hasErrors) {
                alertDiv.removeClass('alert-success').addClass('alert-warning');
                alertDiv.find('i').removeClass('ki-check-circle text-success').addClass('ki-exclamation-triangle text-warning');
            } else {
                alertDiv.removeClass('alert-warning').addClass('alert-success');
                alertDiv.find('i').removeClass('ki-exclamation-triangle text-warning').addClass('ki-check-circle text-success');
            }
            
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
                
                // Show warning if there are errors
                if (data.summary.total_errors > 0) {
                    content += '<div class="alert alert-warning mt-3 mb-0">';
                    content += '<i class="ki-duotone ki-exclamation-triangle fs-2 me-2"></i>';
                    content += '<strong>Warning:</strong> Some data could not be imported due to validation errors. Please review the errors below and fix your Excel file.';
                    content += '</div>';
                }
                content += '</div>';
            }
            
            // Detailed results
            const types = ['users', 'branches', 'memberships', 'classes', 'services'];
            types.forEach(function(type) {
                if (data[type]) {
                    const importedCount = data[type].count || 0;
                    const errorCount = data[type].errors ? data[type].errors.length : 0;
                    
                    // Show card if there are imported items or errors
                    if (importedCount > 0 || errorCount > 0) {
                        content += '<div class="col-md-6">';
                        content += '<div class="card card-flush h-100">';
                        content += '<div class="card-header">';
                        content += '<h6 class="card-title">' + type.charAt(0).toUpperCase() + type.slice(1) + '</h6>';
                        content += '</div>';
                        content += '<div class="card-body">';
                        content += '<div class="d-flex justify-content-between align-items-center mb-2">';
                        content += '<span class="badge badge-light-success">Imported: ' + importedCount + '</span>';
                        content += '<span class="badge badge-light-danger">Errors: ' + errorCount + '</span>';
                        content += '</div>';
                        
                        // Show specific errors for this type
                        if (errorCount > 0 && data[type].errors) {
                            content += '<div class="mt-3">';
                            content += '<h6 class="text-danger fs-7 mb-2">Errors:</h6>';
                            content += '<ul class="list-unstyled fs-7 text-muted">';
                            data[type].errors.forEach(function(error) {
                                content += '<li class="mb-1"><i class="ki-duotone ki-cross-circle fs-2 text-danger me-1"></i>' + error + '</li>';
                            });
                            content += '</ul>';
                            content += '</div>';
                        }
                        
                        content += '</div>';
                        content += '</div>';
                        content += '</div>';
                    }
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
        
        function displayValidationErrors(errors) {
            console.log('Displaying validation errors:', errors);
            
            let errorContent = '<div class="alert alert-warning mb-3">';
            errorContent += '<h6 class="mb-2"><i class="ki-duotone ki-exclamation-triangle fs-2"></i> Validation Errors Found:</h6>';
            errorContent += '<ul class="mb-0">';
            errors.forEach(function(error) {
                errorContent += '<li>' + error + '</li>';
            });
            errorContent += '</ul>';
            errorContent += '</div>';
            
            // Add validation errors to the results section
            $('#resultsContent').append(errorContent);
            
            // Also show in the errors section for visibility
            let errorMessage = '<ul>';
            errors.forEach(function(error) {
                errorMessage += '<li>' + error + '</li>';
            });
            errorMessage += '</ul>';
            
            $('#errorsContent').html(errorMessage);
            $('#importErrors').show();
        }
    });
    </script>
@endsection

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

.badge-light-warning {
    background-color: var(--kt-warning-light);
    color: var(--kt-warning);
}

.alert-warning {
    background-color: var(--kt-warning-light);
    border-color: var(--kt-warning);
    color: var(--kt-warning-dark);
}

.fs-7 {
    font-size: 0.85rem !important;
}

.list-unstyled li {
    padding: 0.25rem 0;
}
</style>
@endpush
