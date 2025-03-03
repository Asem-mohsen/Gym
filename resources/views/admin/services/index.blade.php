@extends('layout.master')

@section('title' , 'Services')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Buttons --}}
                <div class="btn-group w-fit pb-2">
                    <a href="{{ route('services.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add Service</a>
                </div>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp

                        @foreach ($services as $service)

                            <tr>
                                <td>
                                    {{ $i++ }}
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h5 class="mb-0 text-sm">{{$service->name}}</h5>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($service->description, 100, '...') }}
                                </td>
                                <td>
                                    {{$service->duration}}
                                </td>
                                <td>
                                    {{$service->price . " EGP"}}
                                </td>
                                <td class="d-flex justify-content-around gap-1 align-items-baseline">
                                    <a href="{{ route('services.show',$service->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="view">
                                        show
                                    </a>
                                    <a href="{{ route('services.edit',$service->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                        Edit
                                    </a>
                                    <form action="{{ route('services.destroy' ,$service->id )}}" method="post">
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
