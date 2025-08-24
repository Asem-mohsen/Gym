@extends('layout.admin.master')

@section('title' , 'View Admin')

    @section('content')

    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>

                <x-authenticated-user-info />

                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="tab" role="tab" aria-selected="true">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span class="ms-2">Settings</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation" data-bs-toggle="tab" href="" role="tab">
                            <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="{{ Route('admins.index')}}" role="tab" aria-selected="false">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">All Admins</span>
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
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">Profile</p>
                                <button class="btn btn-primary btn-sm ms-auto m-2">Settings</button>
                            </div>
                        </div>
                        <div class="card-body" id="primaryhome">
                            <p class="text-uppercase text-sm">Admin Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Name</label>
                                    <input class="form-control" type="text"  name="name" value="{{$admin->name}}">
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email address</label>
                                    <input class="form-control" type="email" name="email" value="{{$admin->email}}">
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Password</label>
                                    @if (auth()->user()->id == $admin->id)
                                        <input class="form-control" type="password" name="password" value="{{auth()->user()->password}}">
                                    @else
                                        <input class="form-control" type="password" name="password" disabled>
                                    @endif
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Role</label>
                                        <input class="form-control" type="text" name="role" disabled value="{{$admin->roles->name}}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Contact Information</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address</label>
                                        <input class="form-control" type="text" name="address" value="{{$admin->address}}">
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
                                        <label for="example-text-input" class="form-control-label">Phone</label>
                                        <input class="form-control" type="text" name="phone" value="{{$admin->phone}}">
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <p class="text-uppercase text-sm">Control</p>
                            <div class="justify-content-center row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a href="{{ route('admins.edit',$admin->id) }}" class="btn btn-md btn-primary w-100 mt-4 mb-0" > Edit</a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a href="{{ Route('admins.index')}}" class="btn btn-md btn-danger w-100 mt-4 mb-0">Back</a>
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
                                        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="rounded-circle img-fluid border-2 border-white">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                            <div class="d-flex justify-content-center">
                                @if($admin->status == 'active')
                                    <span class="badge badge-success p-2">Active</span>
                                @else
                                    <span class="badge badge-danger p-2">Disactivated</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body pt-0">

                            <div class="text-center mt-4">
                                <h5>
                                    {{$admin->name}}
                                </h5>
                                <div class="h6 font-weight-300">
                                    <i class="ni location_pin mr-2"></i>
                                    {{ $admin->phone }}
                                </div>
                                <div class="h6 mt-4">
                                    <i class="ni business_briefcase-24 mr-2"></i>{{$admin->role->name}} - Gym
                                </div>
                                <div>
                                <i class="ni education_hat mr-2"></i>{{$admin->email}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    @endsection
