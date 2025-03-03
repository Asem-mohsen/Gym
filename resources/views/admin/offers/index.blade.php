@extends('layout.master')

@section('title' , 'Offers')

@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="btn-group w-fit pb-2">
                            <a href="{{ route('offers.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add New Offer</a>
                        </div>
                        <div class="table-responsive p-0">
                            <table id="example1" class="table table-striped align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Total beneficiaries</th>
                                        <th>Remaning days</th>
                                        <th>Ends In</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($offers as $offer)
                                        <tr>
                                            <td> {{ $i++ }} </td>
                                            <td> {{$offer->title}} </td>
                                            <td> {{ \Illuminate\Support\Str::limit($offer->description, 25) }} </td>
                                            <td> {{$offer->users_count}} </td>
                                            <td> {{$offer->remaining_days}} </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ date('d-M-Y' , strtotime($offer->end_date)) }}</p>
                                            </td>
                                            <td>
                                                @if($offer->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Disactivated</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-around gap-1 align-items-baseline">
                                                    <a href="{{ route('offers.edit',$offer->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('offers.destroy' ,$offer->id )}}" method="post">
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
        </div>
    </div>

@endsection

@section('Js')
    @include('_partials.scripts.datatables-init-script')
@stop