@extends('layout.admin.master')

@section('title' , 'Machines')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('machines.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add Machine</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Machine</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Branches</th>
                            <th>Last Maintenance Date</th>
                            <th>Next Maintenance Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($machines as $machine)

                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="{{$machine->getFirstMediaUrl()}}" class="avatar avatar-sm me-3" alt="{{$machine->name}}">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <p>{{$machine->code}}</p>
                                            <h6 class="mb-0 text-sm">{{$machine->name}}</h6>
                                        </div>
                                    </div>
                                    
                                </td>
                                <td>
                                    {{$machine->type}}
                                </td>
                                <td>
                                    @if($machine->status == 'available')
                                        <span class="badge badge-success">{{$machine->status}}</span>
                                    @elseif($machine->status == 'in_use')
                                        <span class="badge badge-secondary">In Use</span>
                                    @elseif($machine->status == 'under_maintenance')
                                        <span class="badge badge-warning">Under Maintenance</span>
                                    @elseif($machine->status == 'needs_maintenance')
                                        <span class="badge badge-danger">Needs Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $machine->branches->take(3)->pluck('name')->implode(', ') }}
                                        @if($machine->branches->count() > 3)
                                            ...
                                        @endif
                                </td>
                                <td>
                                    {{date('d M Y', strtotime($machine->last_maintenance_date))}}
                                </td>
                                <td>
                                    {{date('d M Y', strtotime($machine->next_maintenance_date))}}
                                </td>

                                <td class="d-flex justify-content-around gap-1 align-items-baseline">
                                    <a href="{{ route('machines.show',$machine->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="view">
                                        show
                                    </a>
                                    <a href="{{ route('machines.edit',$machine->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('machines.destroy' ,$machine->id )}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button  class="border-0 bg-transparent p-0 text-danger font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('Js')
    <!-- Page specific script -->
    @include('_partials.scripts.datatables-init-script')
@stop
