@extends('layout.admin.master')

@section('title', 'Check-in Settings')

@section('main-breadcrumb', 'Check-in Settings')
@section('main-breadcrumb-link', route('admin.dashboard'))

@section('sub-breadcrumb', $gym->gym_name)

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Check-in Settings</h3>
                @if($checkinSettings)
                    <div class="card-toolbar">
                        <a href="{{ route('admin.checkin-settings.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Settings
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if($checkinSettings)
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Check-in Methods</h5>
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_self_scan" 
                                           {{ $checkinSettings->enable_self_scan ? 'checked' : '' }}
                                           onchange="toggleMethod('self_scan', this.checked)">
                                    <label class="form-check-label" for="enable_self_scan">
                                        <strong>Self-Scan Check-in</strong>
                                        <br>
                                        <small class="text-muted">Users scan gym's QR code with their phone</small>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enable_gate_scan" 
                                           {{ $checkinSettings->enable_gate_scan ? 'checked' : '' }}
                                           onchange="toggleMethod('gate_scan', this.checked)">
                                    <label class="form-check-label" for="enable_gate_scan">
                                        <strong>Gate-Scan Check-in</strong>
                                        <br>
                                        <small class="text-muted">Staff scan user's personal QR code</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Configuration</h5>
                            <div class="mb-3">
                                <label class="form-label"><strong>Preferred Method:</strong></label>
                                <div class="text-muted">
                                    @switch($checkinSettings->preferred_checkin_method)
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
                                <label class="form-label"><strong>Cooldown Period:</strong></label>
                                <div class="text-muted">{{ $checkinSettings->checkin_cooldown_minutes }} minutes</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Multiple Check-ins:</strong></label>
                                <div class="text-muted">
                                    {{ $checkinSettings->allow_multiple_checkins_per_day ? 'Allowed' : 'Not Allowed' }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Branch Selection:</strong></label>
                                <div class="text-muted">
                                    {{ $checkinSettings->require_branch_selection ? 'Required' : 'Optional' }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Branches:</strong></label>
                                <div class="text-muted">
                                    @if($selectedBranches->count() > 0)
                                        {{ $selectedBranches->pluck('name')->implode(', ') }}
                                    @else
                                        All branches enabled
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Hardware Requirements</h5>
                            @if($checkinSettings->enable_gate_scan)
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-2"></i>For Gate-Scan Method:</h6>
                                    <ul class="mb-0">
                                        <li><strong>QR Code Scanner Hardware</strong> (recommended for high-traffic gyms)</li>
                                        <li><strong>Staff Mobile App</strong> with QR Scanner (alternative option)</li>
                                        <li><strong>Tablet with Camera</strong> for QR Scanning</li>
                                    </ul>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Gate-scan is disabled. No hardware required.
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>Quick Actions</h5>
                            <div class="d-grid gap-2">
                                @role('Admin')
                                    <a href="{{ route('admin.checkin-settings.edit') }}" class="btn btn-outline-dark">
                                        <i class="fas fa-edit me-2"></i>Edit Settings
                                    </a>
                                    <button class="btn btn-outline-secondary" onclick="testQrCode()">
                                        <i class="fas fa-qrcode me-2"></i>Test QR Code
                                    </button>
                                    <a href="{{ route('admin.checkin-settings.stats') }}" class="btn btn-outline-info">
                                        <i class="fas fa-chart-bar me-2"></i>View Statistics
                                    </a>
                                @endrole
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                        <h5>No Check-in Settings Configured</h5>
                        <p class="text-muted">Configure check-in settings to enable QR-based check-in for your gym.</p>
                        @role('Admin')
                            <a href="{{ route('admin.checkin-settings.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Configure Now
                            </a>
                        @endrole
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Instructions</h3>
            </div>
            <div class="card-body">
                <h6><i class="fas fa-lightbulb me-2"></i>Self-Scan Instructions:</h6>
                <p class="text-muted small">📱 Scan the QR code displayed at the gym entrance using your phone camera or the gym app to check in quickly and securely.</p>
                
                <h6><i class="fas fa-lightbulb me-2"></i>Gate-Scan Instructions:</h6>
                <p class="text-muted small">📋 Show your personal QR code (from the gym app or printed card) to our staff at the entrance for check-in. Staff will scan your code to verify your membership.</p>
                
                <hr>
                
                <h6><i class="fas fa-cog me-2"></i>Settings Explained:</h6>
                <ul class="text-muted small">
                    <li><strong>Cooldown:</strong> Prevents spam check-ins (per user)</li>
                    <li><strong>Multiple Check-ins:</strong> Allow users to check in multiple times per day</li>
                    <li><strong>Branch Selection:</strong> Require staff to select branch during gate-scan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        function toggleMethod(method, enabled) {
            fetch('{{ route("admin.checkin-settings.toggle-method") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    method: method,
                    enabled: enabled
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    toastr.success(data.message);
                    // Reload page to update UI
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error('Failed to update setting');
                }
            })
            .catch(error => {
                toastr.error('An error occurred');
            });
        }

        function testQrCode() {
            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
            button.disabled = true;

            fetch('{{ route("admin.checkin-settings.test-qr") }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Create QR code URL
                const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(data.qr_url)}`;
                
                // Try to open in new window
                const qrWindow = window.open('', '_blank', 'width=400,height=600');
                
                if (qrWindow) {
                    qrWindow.document.write(`
                        <html>
                            <head>
                                <title>Test QR Code - {{ $gym->gym_name }}</title>
                                <style>
                                    body { 
                                        font-family: Arial, sans-serif; 
                                        text-align: center; 
                                        padding: 20px; 
                                        background: #f8f9fa;
                                    }
                                    .qr-container { 
                                        margin: 20px 0; 
                                    }
                                </style>
                            </head>
                            <body>
                                <h2>{{ $gym->gym_name }}</h2>
                                <h3>Test QR Code</h3>
                                <div class="qr-container">
                                    <img src="${qrImageUrl}" alt="QR Code" class="qr-code">
                                </div>
                                <div>
                                    <strong>URL:</strong><br>
                                    ${data.qr_url}
                                </div>
                                <p><small>Scan this QR code to test the check-in system</small></p>
                                <button onclick="window.close()" style="padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Close</button>
                            </body>
                        </html>
                    `);
                    qrWindow.document.close();
                } else {
                    // Fallback: show in modal or alert
                    alert(`QR Code URL: ${data.qr_url}\n\nCopy this URL and test it manually.`);
                }
                
                // Show success message
                toastr.success('QR code generated successfully!');
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Failed to generate test QR code: ' + error.message);
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
    </script>
@endsection
