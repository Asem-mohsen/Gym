@extends('layout.user.master')

@section('title', 'Staff Scanner - ' . $gym->name)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-camera me-2"></i>
                        Staff Check-in Scanner - {{ $gym->name }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center mb-4">
                                <div class="bg-light p-4 rounded">
                                    <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                                    <h5>Staff Scanner</h5>
                                    <p class="text-muted">Scan member QR codes for check-in</p>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Staff Instructions:</h6>
                                <p class="mb-2">📋 Ask members to show their personal QR code (from the gym app or printed card) and scan it using the camera below or enter the token manually.</p>
                                <p class="mb-0"><strong>Hardware Requirements:</strong> QR Code Scanner Hardware (recommended) or Staff Mobile App with QR Scanner</p>
                            </div>

                            <!-- Scanner Interface -->
                            <div class="scanner-container">
                                <div class="text-center mb-3">
                                    <button id="start-scanner" class="btn btn-primary btn-lg">
                                        <i class="fas fa-camera me-2"></i>
                                        Start Scanner
                                    </button>
                                    <button id="stop-scanner" class="btn btn-secondary btn-lg" style="display: none;">
                                        <i class="fas fa-stop me-2"></i>
                                        Stop Scanner
                                    </button>
                                </div>

                                <div id="scanner-view" class="text-center" style="display: none;">
                                    <video id="scanner-video" width="100%" style="max-width: 400px; border: 2px solid #ddd; border-radius: 8px;"></video>
                                    <div class="mt-2">
                                        <small class="text-muted">Position the QR code within the frame</small>
                                    </div>
                                </div>

                                <!-- Manual Entry -->
                                <div class="mt-4">
                                    <h6><i class="fas fa-keyboard me-2"></i>Manual Entry</h6>
                                    <form id="manual-checkin-form" action="{{ route('user.checkin.gate', $gym->slug) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="token" class="form-label">QR Code Token</label>
                                            <input type="text" class="form-control" id="token" name="token" placeholder="Enter QR code token manually">
                                        </div>
                                        <div class="mb-3">
                                            <label for="branch_id" class="form-label">Branch (Optional)</label>
                                            <select class="form-select" id="branch_id" name="branch_id">
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-check me-2"></i>
                                            Process Check-in
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Check-in Results -->
                            <div id="checkin-results" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="fas fa-user-check me-2"></i>Check-in Result</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="user-info"></div>
                                        <div id="checkin-status"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="mt-4">
                                <h6><i class="fas fa-history me-2"></i>Recent Check-ins</h6>
                                <div id="recent-checkins" class="list-group">
                                    <!-- Recent check-ins will be loaded here -->
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="mt-4">
                                <h6><i class="fas fa-chart-bar me-2"></i>Today's Stats</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="bg-light p-3 rounded">
                                            <h4 id="today-checkins">0</h4>
                                            <small class="text-muted">Check-ins</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light p-3 rounded">
                                            <h4 id="unique-users">0</h4>
                                            <small class="text-muted">Unique Users</small>
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
let scanner = null;
let videoStream = null;

document.addEventListener('DOMContentLoaded', function() {
    loadRecentCheckins();
    loadTodayStats();
    
    document.getElementById('start-scanner').addEventListener('click', startScanner);
    document.getElementById('stop-scanner').addEventListener('click', stopScanner);
});

function startScanner() {
    const video = document.getElementById('scanner-video');
    const startBtn = document.getElementById('start-scanner');
    const stopBtn = document.getElementById('stop-scanner');
    const scannerView = document.getElementById('scanner-view');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            videoStream = stream;
            video.srcObject = stream;
            video.play();

            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
            scannerView.style.display = 'block';

            // Start QR code detection
            scanQRCode();
        })
        .catch(function(err) {
            console.error('Error accessing camera:', err);
            alert('Unable to access camera. Please check permissions.');
        });
}

function stopScanner() {
    const video = document.getElementById('scanner-video');
    const startBtn = document.getElementById('start-scanner');
    const stopBtn = document.getElementById('stop-scanner');
    const scannerView = document.getElementById('scanner-view');

    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }

    startBtn.style.display = 'inline-block';
    stopBtn.style.display = 'none';
    scannerView.style.display = 'none';
}

function scanQRCode() {
    const video = document.getElementById('scanner-video');
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

    const code = jsQR(imageData.data, imageData.width, imageData.height);

    if (code) {
        console.log('QR Code detected:', code.data);
        processQRCode(code.data);
        return;
    }

    // Continue scanning
    if (videoStream) {
        requestAnimationFrame(scanQRCode);
    }
}

function processQRCode(qrData) {
    // Extract token from QR data (assuming it's a URL with token parameter)
    const url = new URL(qrData);
    const token = url.searchParams.get('token') || qrData;

    // Send to server for validation and check-in
    fetch('{{ route("user.checkin.gate", $gym->slug) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            token: token,
            branch_id: document.getElementById('branch_id').value
        })
    })
    .then(response => response.json())
    .then(data => {
        showCheckinResult(data);
        if (data.success) {
            loadRecentCheckins();
            loadTodayStats();
        }
    })
    .catch(error => {
        console.error('Error processing check-in:', error);
        showCheckinResult({ success: false, message: 'Error processing check-in' });
    });
}

function showCheckinResult(data) {
    const resultsDiv = document.getElementById('checkin-results');
    const userInfoDiv = document.getElementById('user-info');
    const statusDiv = document.getElementById('checkin-status');

    if (data.success) {
        userInfoDiv.innerHTML = `
            <div class="text-center mb-3">
                <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                <h5>${data.data.user.name}</h5>
                <p class="text-muted">${data.data.user.email}</p>
            </div>
        `;
        statusDiv.innerHTML = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
            </div>
        `;
    } else {
        userInfoDiv.innerHTML = `
            <div class="text-center mb-3">
                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                <h5>Check-in Failed</h5>
            </div>
        `;
        statusDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${data.message}
            </div>
        `;
    }

    resultsDiv.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        resultsDiv.style.display = 'none';
    }, 5000);
}

function loadRecentCheckins() {
    // Load recent check-ins via AJAX
    fetch('{{ route("user.checkin.history", $gym->slug) }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recent-checkins');
            container.innerHTML = '';
            
            data.data.history.checkins.slice(0, 5).forEach(checkin => {
                container.innerHTML += `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${checkin.user.name}</strong>
                                <br>
                                <small class="text-muted">${checkin.checkin_type_label}</small>
                            </div>
                            <small class="text-muted">${new Date(checkin.created_at).toLocaleTimeString()}</small>
                        </div>
                    </div>
                `;
            });
        });
}

function loadTodayStats() {
    // Load today's stats via AJAX
    fetch('{{ route("user.checkin.stats", $gym->slug) }}?period=today')
        .then(response => response.json())
        .then(data => {
            document.getElementById('today-checkins').textContent = data.data.stats.total_checkins;
            document.getElementById('unique-users').textContent = data.data.stats.unique_users;
        });
}
</script>
@endpush
@endsection
