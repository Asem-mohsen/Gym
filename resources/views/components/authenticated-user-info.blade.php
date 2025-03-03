<div class="col-auto my-auto">
    <div class="h-100">
        <h5 class="mb-1">
            {{ auth()->user()->name }}
        </h5>
        <p class="mb-0 font-weight-bold text-sm">
            {{ auth()->user()->roles->name ?? 'No Role Assigned' }}
        </p>
    </div>
</div>
