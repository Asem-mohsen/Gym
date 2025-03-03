@extends('layout.master')

@section('title' , 'Users')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('users.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add New User</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <img src="{{ asset('assets/dist/img/avatar.png') }}" class="avatar avatar-sm me-3" alt="{{$user->name}}">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{$user->name}}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$user->email}}</td>
                                <td>
                                    {{ $user->phone }}
                                </td>
                                <td>
                                    @if ($user->address)
                                        {{ \Illuminate\Support\Str::limit($user->address, 25) }}
                                    @else
                                        No data
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d F Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-around gap-1 align-items-baseline">
                                        <a href="{{ Route('users.show',$user->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Your Profile">
                                            Check
                                        </a>
                                        <a href="{{ route('users.edit',$user->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                            Edit
                                        </a>
                                        <form action="{{ route('users.destroy' ,$user->id )}}" method="post">
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
