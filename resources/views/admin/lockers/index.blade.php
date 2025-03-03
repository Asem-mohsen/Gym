@extends('layout.master')

@section('title' , 'Lockers')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h1 class="text-center">Locker Room</h1>
                <div class="row">
                    @foreach($lockers as $locker)
                        <div class="col-md-3 mb-4">
                            <div class="p-4 text-center border rounded" style="border: 4px solid {{ $locker->is_locked ? 'red' : 'green' }} !important;">
                                <h3>Locker {{ $locker->locker_number }}</h3>
                                <button class="btn {{ $locker->is_locked ? 'btn-danger' : 'btn-success' }}" onclick="toggleLocker({{ $locker->id }})">
                                    {{ $locker->is_locked ? 'Unlock' : 'Lock' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('Js')
    @include('admin.lockers.scripts.locker-rooms-script')
@stop
