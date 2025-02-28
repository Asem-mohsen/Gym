@extends('layout.master')

@section('title' , 'Roles')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('roles.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add New Role</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Number of Users</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($roles as $role)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm">{{$role->name}}</h6>
                                </td>
                                <td>{{ $role->users_count }}</td>
                                <td class="d-flex justify-content-around align-items-baseline">
                                    <a href="{{ Route('roles.edit',$role->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View Role">
                                        Check
                                    </a>
                                    <form action="{{ route('roles.destroy' ,$role->id )}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button  class="border-0 bg-transparent p-0 text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Delete">
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
    @include('_partials.scripts.datatables-init-script')
@stop
