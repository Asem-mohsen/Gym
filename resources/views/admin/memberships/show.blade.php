@extends('layout.admin.master')

@section('title' , $membership->name . ' Membership')

@section('content')

    @include('admin.memberships.style.custom-style')
        <div class="card shadow-lg mx-4 card-profile-bottom">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src="{{ asset('assets/dist/img/avatar.png') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div>
                    
                    <x-authenticated-user-info />

                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="pricing">
                <div class="plan">
                    <h2>{{$membership->name . ' Plan'}}</h2>
                    <div class="price">{{ $membership->price . ' EGP' }}</div>
                    <div>
                        <p>{{$membership->subtitle}}</p>
                    </div>
                    <ul class="features">
                        @foreach($membership->features as $feature)
                            <li><i class="fa fa-check-circle"></i>{{$feature->getTranslation('name', app()->getLocale())}}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('membership.edit',$membership->id) }}" class="edit-plan text-white font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    @endsection
