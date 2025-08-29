@extends('layout.admin.master')

@section('title', 'Contact Messages')

@section('main-breadcrumb', 'Contact Messages')
@section('main-breadcrumb-link', route('admin.contacts.index'))

@section('sub-breadcrumb', 'Index')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">

        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search" />
                    </div>
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-table-toolbar="base">
                    <span class="text-muted">Total Messages: {{ $contacts->count() }}</span>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">

            <table class="table table-striped table-row-dashed align-middle table-row-dashed fs-6 gy-5" id="kt_table">
                <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0 table-head">
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Status</th>
                        @can('reply_to_contacts')
                            <th>Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                    @forelse ($contacts as $key => $contact)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ $contact->name }}</h6>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $contact->email }}" class="text-primary text-hover-primary">
                                    {{ $contact->email }}
                                </a>
                            </td>
                            <td>
                                <a href="tel:{{ $contact->phone }}" class="text-primary text-hover-primary">
                                    {{ $contact->phone }}
                                </a>
                            </td>
                            <td>
                                @if ($contact->message)
                                    <div class="text-wrap" style="max-width: 300px;">
                                        {{ \Illuminate\Support\Str::limit($contact->message, 100) }}
                                        @if (strlen($contact->message) > 100)
                                            <button type="button" class="btn btn-sm btn-link p-0 ms-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#messageModal{{ $contact->id }}">
                                                Read More
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">No message</span>
                                @endif
                            </td>
                            <td>{{ $contact->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @if ($contact->is_answered)
                                    <span class="badge bg-success text-white">Answered</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            @can('reply_to_contacts')
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                            <x-table-icon-link 
                                                :route="'mailto:{{ $contact->email }}?subject=Re: Contact Message from {{ $siteSetting->gym_name }}'" 
                                                colorClass="primary"
                                                title="Reply"
                                                iconClasses="fa-solid fa-reply"
                                            />

                                            @if (!$contact->is_answered)
                                                <button type="button" 
                                                        class="btn btn-sm mark-answered-btn" 
                                                        data-contact-id="{{ $contact->id }}"
                                                        data-bs-toggle="tooltip" 
                                                        title="Mark as Answered">
                                                    <i class="ki-duotone ki-check fs-2 text-success"></i>
                                                </button>
                                            @endif
                                    </div>
                                </td>
                            @endcan
                        </tr>

                        <!-- Message Modal -->
                        @if ($contact->message && strlen($contact->message) > 100)
                            <div class="modal fade" id="messageModal{{ $contact->id }}" tabindex="-1" aria-labelledby="messageModalLabel{{ $contact->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="messageModalLabel{{ $contact->id }}">
                                                Message from {{ $contact->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Name:</strong> {{ $contact->name }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Email:</strong> 
                                                    <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Phone:</strong> 
                                                    <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Date:</strong> {{ $contact->created_at->format('d M Y, h:i A') }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <strong>Message:</strong>
                                                    <div class="mt-2 p-3 bg-light rounded">
                                                        {{ $contact->message }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            @can('reply_to_contacts')
                                                <a href="mailto:{{ $contact->email }}?subject=Re: Contact Message from {{ $siteSetting->gym_name }}" 
                                                class="btn btn-primary">
                                                    Reply via Email
                                                </a>
                                                @if (!$contact->is_answered)
                                                    <button type="button" 
                                                            class="btn btn-success mark-answered-btn" 
                                                            data-contact-id="{{ $contact->id }}">
                                                        Mark as Answered
                                                    </button>
                                                @endif
                                            @endcan
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ki-duotone ki-message-text-2 fs-2x mb-3"></i>
                                    <p>No contact messages found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('_partials.dataTable-script')
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Mark as answered functionality
    $('.mark-answered-btn').on('click', function() {
        var contactId = $(this).data('contact-id');
        var button = $(this);
        
        $.ajax({
            url: '/admin/contacts/' + contactId + '/mark-answered',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
            },
            data: {},
            success: function(response) {
                if (response.success) {
                    // Update the status badge
                    var row = button.closest('tr');
                    var statusCell = row.find('td:nth-child(7)');
                    statusCell.html('<span class="badge bg-success text-white">Answered</span>');
                    
                    // Remove the mark as answered button
                    button.remove();
                    
                    // Show success message
                    toastr.success(response.message);
                } else {
                    toastr.error('Error marking contact as answered');
                }
            },
            error: function() {
                toastr.error('Error marking contact as answered');
            }
        });
    });
});
</script>
@endsection
