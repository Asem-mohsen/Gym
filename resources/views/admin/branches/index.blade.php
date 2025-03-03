@extends('layout.master')

@section('title' , 'Branches')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('branches.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add New Branch</a>
                </div>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>Manager</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Subscribers</th>
                            <th>Created at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($branches as $branch)
                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td> {{$branch->name}} </td>
                                <td> {{$branch->manager->name}}</td>
                                <td> {{$branch->location}} </td>
                                <td> {{$branch->type}}</td>
                                <td> {{$branch->subscriptions_count}}</td>
                                <td> {{date('d-M-Y' , strtotime( $branch->created_at))}} </td>
                                <td class="d-flex justify-content-around gap-1 align-items-baseline">
                                    <a href="{{ route('branches.show',$branch->id)}}" target="_blank" class="text-success font-weight-bold text-xs" data-toggle="tooltip" data-original-title="check">
                                        Check
                                    </a>
                                    <a href="{{ route('branches.edit',$branch->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('branches.destroy' ,$branch->id )}}" method="post">
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