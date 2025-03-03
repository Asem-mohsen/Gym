@extends('layout.master')

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
            </div>
        </div>
    @endsection
