@extends('layout.admin.master')

@section('title' , 'Profile')

    @section('content')
        <div class="card shadow-lg mx-4 card-profile-bottom">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src="{{ asset('assets/dist/img/avatar.png') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $user->name }}
                            </h5>
                            <div class="d-flex align-items-baseline" style="gap:20px;">
                                <p class="mb-0 font-weight-bold text-sm">
                                    @if($user->status == 1)
                                        <span class="badge badge-success p-2">Active</span>
                                    @else
                                        <span class="badge badge-danger p-2">Disactivated</span>
                                    @endif
                                </p>
                                <p>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success p-2">Verified</span>
                                    @else
                                        <span class="badge badge-warning p-2">Unverified</span>
                                    @endif
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="tab" role="tab" aria-selected="true">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <span class="ms-2">Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " data-bs-toggle="tab" role="tab" aria-selected="false">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <span class="ms-2">Users</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header pb-2">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">Profile</p>
                                    <a class="btn btn-primary btn-sm ms-auto m-2">Settings</a>
                                </div>
                            </div>
                            <div class="card-body" id="primaryhome">
                                <p class="text-uppercase text-sm">User Information</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Name</label>
                                            <input class="form-control" type="text"  name="name" value="{{ $user->name }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Email address</label>
                                            <input class="form-control" type="email" name="email" value="{{ $user->email }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Country</label>
                                            <input class="form-control" type="text" value="Egypt">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Address</label>
                                            <textarea class="form-control" name="address">{{$user->address}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Phone</label>
                                            <input class="form-control" type="text" name="phone" value="{{$user->phone}}">
                                        </div>
                                    </div>

                                </div>
                                <hr class="horizontal dark">
                                <p class="text-uppercase text-sm">Orders</p>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Membership</p>
                                                            <h5 class="font-weight-bolder">
                                                                {{ $user->membership->name }}
                                                            </h5>
                                                            <p class="mb-0">
                                                            <span class="text-success text-sm font-weight-bolder">date</span>
                                                                Renewal Date
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">User Paid</p>
                                                            <h5 class="font-weight-bolder">
                                                                EGP {{ $amountPaid }}
                                                            </h5>
                                                            <p class="mb-0">
                                                            <span class="text-success text-sm font-weight-bolder">+55%</span>
                                                                for the subscription
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-primary shadow-primary text-center rounded-circle">
                                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <hr class="horizontal dark">

                                <p class="text-uppercase text-sm">Control</p>
                                    <div class="justify-content-center row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                @if($user->status == 1)
                                                    <button type="submit" name="Deactivate" class="btn btn-md bg-danger w-100 mt-4 mb-0">Deactivate Account</button>
                                                @else
                                                    <button type="submit" name="Activate" class="btn btn-md bg-success w-100 mt-4 mb-0">Activate</button>
                                                @endif
                                            </div>
                                        </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <a href="{{ Route('users.index')}}" class="btn btn-md btn-info w-100 mt-4 mb-0">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Side Card -->
                    <div class="col-md-4">
                        <div class="card card-profile">
                            <img src="{{ asset('assets/dist/img/web/Background.jpg') }}" alt="Image placeholder" class="card-img-top">
                            <div class="row justify-content-center">
                                <div class="col-4 col-lg-4 order-lg-2">
                                    <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                        <a>
                                            <img src="{{ asset('assets/dist/img/avatar.png') }}" class="rounded-circle img-fluid border-2 border-white">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                                <div class="d-flex justify-content-center">
                                    @if($user->status == 1)
                                        <span class="badge badge-success p-2">Active</span>
                                    @else
                                        <span class="badge badge-danger p-2">Disactivated</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body pt-0">
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-grid text-center">
                                            <span class="text-lg font-weight-bolder"> {{ $user->membership->name }}</span>
                                            <span class="text-sm opacity-8">Membership</span>
                                        </div>
                                        <div class="d-grid text-center mx-4">
                                            <span class="text-lg font-weight-bolder">
                                                EGP {{ $amountPaid }}
                                            </span>
                                            <span class="text-sm opacity-8">Total Paid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <h5>
                                {{ $user->name }}<span class="font-weight-light"></span>
                                </h5>
                                <div class="h6 font-weight-300">
                                <i class="ni location_pin mr-2"></i> {{ $user->address}}
                                </div>
                                <div class="h6 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>
                                    {{ $user->phone }}
                                </div>
                                <div>
                                <i class="ni education_hat mr-2"></i>{{ $user->email }}
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endsection
