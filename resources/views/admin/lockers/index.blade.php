@extends('layout.admin.master')

@section('title' , 'Lockers')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Locker Room Dashboard</h2>
        <div class="row">
            @foreach($lockers as $locker)
                <div class="col-md-3 mb-4">
                    <div class="card 
                        {{ $locker->is_locked ? 'border-danger' : 'border-success' }} 
                        shadow-sm"
                        style="border-width:2px;">
                        <div class="card-body text-center">
                            <h5 class="card-title">Locker #{{ $locker->id }}</h5>
                            <p class="card-text">
                                Status: 
                                <span class="badge {{ $locker->is_locked ? 'bg-danger' : 'bg-success' }}">
                                    {{ $locker->is_locked ? 'Locked' : 'Unlocked' }}
                                </span>
                            </p>
                            <div class="d-grid gap-2">
                                @if($locker->is_locked)
                                    <button class="btn btn-warning btn-sm" onclick="unlockLocker({{ $locker->id }})">Unlock</button>
                                    <button class="btn btn-secondary btn-sm" onclick="showRecoveryModal({{ $locker->id }})">Unlock with Recovery Token</button>
                                @else
                                    <button class="btn btn-primary btn-sm" onclick="lockLocker({{ $locker->id }})">Lock</button>
                                @endif
                                <button class="btn btn-info btn-sm" onclick="generateRecoveryToken({{ $locker->id }})">Generate Recovery Token</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recovery Token Modal -->
    <div class="modal fade" id="recoveryModal" tabindex="-1" aria-labelledby="recoveryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="recoveryForm" onsubmit="submitRecoveryToken(event)">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="recoveryModalLabel">Unlock with Recovery Token</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="recoveryLockerId">
            <div class="mb-3">
                <label for="recovery_token" class="form-label">Recovery Token</label>
                <input type="text" class="form-control" id="recovery_token" required>
            </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-success">Unlock</button>
            </div>
        </div>
        </form>
    </div>
    </div>
@endsection

@section('js')
@vite(['resources/js/app.js'])
<script>
    // Real-time updates using Laravel Echo
    window.Echo.channel('lockers')
        .listen('LockerUpdatedEvent', (e) => {
            console.log('Locker updated:', e);
            location.reload();
        });

    function lockLocker(lockerId) {
        let password = prompt("Enter password to lock:");
        if (!password) return;
        fetch(`/admin/lockers/${lockerId}/lock`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ password })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) alert(data.message);
            else location.reload();
        });
    }

    function unlockLocker(lockerId) {
        let password = prompt("Enter password to unlock:");
        if (!password) return;
        fetch(`/admin/lockers/${lockerId}/unlock`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ password })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) alert(data.message);
            else location.reload();
        });
    }

    function generateRecoveryToken(lockerId) {
        if (!confirm("Generate a new recovery token? This will invalidate any previous token.")) return;
        fetch(`/admin/lockers/${lockerId}/recovery-token`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                prompt("Copy this recovery token and keep it safe:", data.recovery_token);
            } else {
                alert(data.message || "Failed to generate token.");
            }
        });
    }

    function showRecoveryModal(lockerId) {
        document.getElementById('recoveryLockerId').value = lockerId;
        document.getElementById('recovery_token').value = '';
        var modal = new bootstrap.Modal(document.getElementById('recoveryModal'));
        modal.show();
    }

    function submitRecoveryToken(event) {
        event.preventDefault();
        let lockerId = document.getElementById('recoveryLockerId').value;
        let token = document.getElementById('recovery_token').value;
        fetch(`/admin/lockers/${lockerId}/unlock-with-token`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ recovery_token: token })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) alert(data.message);
            else location.reload();
        });
    }
</script>
@endsection
