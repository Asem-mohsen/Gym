@extends('layout.admin.master')

@section('title', 'Edit Check-in Settings')

@section('main-breadcrumb', 'Check-in Settings')
@section('main-breadcrumb-link', route('admin.checkin-settings.index'))

@section('sub-breadcrumb', 'Edit Settings')

@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.checkin-settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Check-in Settings</h3>
                </div>
                <div class="card-body">
                    <!-- Check-in Methods -->
                    <h5 class="mb-4"><i class="fas fa-qrcode me-2"></i>Check-in Methods</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_self_scan" name="enable_self_scan" value="1" 
                                       {{ $checkinSetting->enable_self_scan ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_self_scan">
                                    <strong>Self-Scan Check-in</strong>
                                    <br>
                                    <small class="text-muted">Users scan gym's QR code with their phone</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enable_gate_scan" name="enable_gate_scan" value="1"
                                       {{ $checkinSetting->enable_gate_scan ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_gate_scan">
                                    <strong>Gate-Scan Check-in</strong>
                                    <br>
                                    <small class="text-muted">Staff scan user's personal QR code</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Preferred Method -->
                    <div class="mb-4">
                        <label for="preferred_checkin_method" class="form-label">Preferred Check-in Method</label>
                        <select class="form-select" id="preferred_checkin_method" name="preferred_checkin_method" required>
                            <option value="both" {{ $checkinSetting->preferred_checkin_method === 'both' ? 'selected' : '' }}>
                                Both Methods Available
                            </option>
                            <option value="self_scan" {{ $checkinSetting->preferred_checkin_method === 'self_scan' ? 'selected' : '' }}>
                                Self-Scan (Users scan gym QR)
                            </option>
                            <option value="gate_scan" {{ $checkinSetting->preferred_checkin_method === 'gate_scan' ? 'selected' : '' }}>
                                Gate-Scan (Staff scan user QR)
                            </option>
                        </select>
                        <div class="form-text">This determines which method is promoted to users</div>
                    </div>

                    <!-- Configuration Options -->
                    <h5 class="mb-4"><i class="fas fa-cog me-2"></i>Configuration Options</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="checkin_cooldown_minutes" class="form-label">Check-in Cooldown (minutes)</label>
                            <input type="number" class="form-control" id="checkin_cooldown_minutes" name="checkin_cooldown_minutes" 
                                   value="{{ $checkinSetting->checkin_cooldown_minutes }}" min="1" max="60" required>
                            <div class="form-text">Prevents spam check-ins (per user)</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="allow_multiple_checkins_per_day" name="allow_multiple_checkins_per_day" value="1"
                                       {{ $checkinSetting->allow_multiple_checkins_per_day ? 'checked' : '' }}>
                                <label class="form-check-label" for="allow_multiple_checkins_per_day">
                                    <strong>Allow Multiple Check-ins Per Day</strong>
                                    <br>
                                    <small class="text-muted">Users can check in multiple times (e.g., leave and return)</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="require_branch_selection" name="require_branch_selection" value="1"
                                       {{ $checkinSetting->require_branch_selection ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_branch_selection">
                                    <strong>Require Branch Selection</strong>
                                    <br>
                                    <small class="text-muted">Staff must select branch during gate-scan check-in</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Branch Selection -->
                    @if($branches->count() > 0)
                        <h5 class="mb-4"><i class="fas fa-building me-2"></i>Enabled Branches</h5>
                        <div class="mb-4">
                            <div class="form-text mb-2">Select which branches can use check-in system (leave empty for all branches)</div>
                            <div class="row">
                                @foreach($branches as $branch)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="branch_{{ $branch->id }}" 
                                                   name="enabled_branches[]" value="{{ $branch->id }}"
                                                   {{ in_array($branch->id, $checkinSetting->enabled_branches ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="branch_{{ $branch->id }}">
                                                {{ $branch->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Settings
                        </button>
                        <a href="{{ route('admin.checkin-settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Current Settings</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label"><strong>Preferred Method:</strong></label>
                    <div class="text-muted">
                        @switch($checkinSetting->preferred_checkin_method)
                            @case('self_scan')
                                Self-Scan (Users scan gym QR)
                                @break
                            @case('gate_scan')
                                Gate-Scan (Staff scan user QR)
                                @break
                            @case('both')
                                Both Methods Available
                                @break
                        @endswitch
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><strong>Enabled Methods:</strong></label>
                    <div class="text-muted">
                        @if($checkinSetting->enable_self_scan)
                            <span class="badge bg-primary text-white me-1">Self-Scan</span>
                        @endif
                        @if($checkinSetting->enable_gate_scan)
                            <span class="badge bg-success text-white me-1">Gate-Scan</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Cooldown Period:</strong></label>
                    <div class="text-muted">{{ $checkinSetting->checkin_cooldown_minutes }} minutes</div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Multiple Check-ins:</strong></label>
                    <div class="text-muted">
                        {{ $checkinSetting->allow_multiple_checkins_per_day ? 'Allowed' : 'Not Allowed' }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Branch Selection:</strong></label>
                    <div class="text-muted">
                        {{ $checkinSetting->require_branch_selection ? 'Required' : 'Optional' }}
                    </div>
                </div>

                <hr>

                <h6><i class="fas fa-lightbulb me-2"></i>Instructions:</h6>
                <p class="text-muted small">📱 Self-Scan: Users scan gym QR code with their phone</p>
                <p class="text-muted small">📋 Gate-Scan: Staff scan user's personal QR code</p>
                
                <hr>

                <h6><i class="fas fa-info-circle me-2"></i>Hardware Requirements:</h6>
                @if($checkinSetting->enable_gate_scan)
                    <div class="alert alert-info">
                        <strong>For Gate-Scan Method:</strong>
                        <ul class="mb-0 mt-2">
                            <li>QR Code Scanner Hardware (recommended)</li>
                            <li>Staff Mobile App with QR Scanner</li>
                            <li>Tablet with Camera for QR Scanning</li>
                        </ul>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Gate-scan is disabled. No hardware required.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update preferred method based on enabled methods
    function updatePreferredMethod() {
        const selfScanEnabled = document.getElementById('enable_self_scan').checked;
        const gateScanEnabled = document.getElementById('enable_gate_scan').checked;
        const preferredSelect = document.getElementById('preferred_checkin_method');
        
        if (!selfScanEnabled && !gateScanEnabled) {
            // At least one method must be enabled
            return;
        }
        
        if (selfScanEnabled && gateScanEnabled) {
            preferredSelect.value = 'both';
        } else if (selfScanEnabled) {
            preferredSelect.value = 'self_scan';
        } else if (gateScanEnabled) {
            preferredSelect.value = 'gate_scan';
        }
    }
    
    document.getElementById('enable_self_scan').addEventListener('change', updatePreferredMethod);
    document.getElementById('enable_gate_scan').addEventListener('change', updatePreferredMethod);
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const selfScanEnabled = document.getElementById('enable_self_scan').checked;
        const gateScanEnabled = document.getElementById('enable_gate_scan').checked;
        
        if (!selfScanEnabled && !gateScanEnabled) {
            e.preventDefault();
            alert('At least one check-in method must be enabled.');
            return false;
        }
    });
});
</script>
@endpush
@endsection
