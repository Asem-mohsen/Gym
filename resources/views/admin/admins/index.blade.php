@extends('layout.master')
@section('title' , 'Admins')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('admins.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add New Admin</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($admins as $admin)

                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="avatar avatar-sm me-3" alt="user1">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{$admin->name}}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$admin->email}}</td>
                                <td>{{$admin->roles->name}}</td>
                                <td>{{$admin->phone}}</td>
                                <td>
                                    @if($admin->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Disactivated</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-around gap-1 align-items-baseline">
                                        <a href="{{ route('admins.show',$admin->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Your Profile">
                                            @if (auth()->user()->id == $admin->id)
                                                profile
                                            @else
                                                Check
                                            @endif
                                        </a>
                                        <a href="{{ route('admins.edit',$admin->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                            Edit
                                        </a>
                                        <form action="{{ route('admins.destroy' ,$admin->id )}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button class="border-0 bg-transparent p-0 text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
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
    @include('_partials.scripts.datatables-init-script')
@stop
