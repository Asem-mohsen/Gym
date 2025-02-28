@extends('layout.master')

@section('title' , 'Memberships')

@section('content')

    @include('admin.memberships.style.custom-style')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Buttons --}}
                    <div class="btn-group w-fit pb-2">
                        <a href="{{ route('membership.create') }}" class="btn btn-dark p-2"><i class="fa-solid fa-plus mr-1"></i>Add New Membership</a>
                    </div>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Period</th>
                                <th>Num. Subscriptions</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($memberships as $membership)
                                <tr>
                                    <td>
                                        {{ $i++ }}
                                    </td>
                                    <td>
                                        <h6 class="mb-0 text-sm">{{$membership->name}}</h6>
                                    </td>
                                    <td>{{$membership->period}}</td>
                                    <td>{{$membership->bookings_count }}</td>
                                    <td>
                                        @if ($membership->description)
                                            {{ \Illuminate\Support\Str::limit($membership->description, 25) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $membership->price . ' EGP' }}</td>
                                    <td>
                                        @if($membership->status === 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Disactivated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around gap-1 align-items-baseline">
                                            <a href="{{ Route('membership.show',$membership->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Show membership">
                                                Check
                                            </a>
                                            <a href="{{ route('membership.edit',$membership->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                                Edit
                                            </a>
                                            <form action="{{ route('membership.destroy' ,$membership->id )}}" method="post">
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

            <div class="card">
                <div class="pricing">
                    @foreach ($memberships as $membership)
                        <div class="plan {{ $maxBookings > 0 && $membership->bookings_count == $maxBookings ? 'popular' : '' }}">
                            <h2>{{$membership->name}}</h2>
                            <div class="price">{{ $membership->price . ' EGP' }}</div>
                            <div>
                                <p>{{$membership->description}}</p>
                            </div>
                            <ul class="features">
                                <li><i class="fas fa-check-circle"></i>{{$membership->period}}</li>
                                <li><i class="fas fa-check-circle"></i> Unlimited Websites</li>
                                <li><i class="fas fa-check-circle"></i> 1 User</li>
                                <li><i class="fas fa-check-circle"></i> 100MB Space/website</li>
                                <li><i class="fas fa-check-circle"></i> Continuous deployment</li>
                                <li><i class="fas fa-times-circle"></i> No priority support</li>
                            </ul>
                            <a href="{{ route('membership.edit',$membership->id) }}" class="edit-plan text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                                Edit
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@section('Js')
    @include('_partials.scripts.datatables-init-script')
@stop
