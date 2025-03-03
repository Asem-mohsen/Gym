@extends('layout.master')

@section('title' , 'Add Membership')

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
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center " data-bs-toggle="tab" role="tab" aria-selected="true">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span class="ms-2">New Membership</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center " href="{{route('membership.index')}}" data-bs-toggle="tab"  role="tab" aria-selected="false">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">All Memberships</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <form action="{{ route('membership.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <span class="btn btn-dark btn-sm ms-auto m-2">Add</span>
                                <p class="mb-0">New Membership</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Membership Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_en" class="form-control-label">Membership Name (English)</label>
                                        <input class="form-control" type="text" name="name[en]" id="name_en" value="{{ old('name.en') }}" required>
                                    </div>
                                    @error('name.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_ar" class="form-control-label">Membership Name (Arabic)</label>
                                        <input class="form-control" type="text" name="name[ar]" id="name_ar" value="{{ old('name.ar') }}" required>
                                    </div>
                                    @error('name.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="period" class="form-control-label">Period</label>
                                    <select class="form-control" name="period" id="period">
                                        <option value="Month">Month</option>
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="Year">Year</option>
                                        <option value="2 Years">2 Years</option>
                                        <option value="3 Years">3 Years</option>
                                        <option value="4 Years">4 Years</option>
                                        <option value="5 Years">5 Years</option>
                                        <option value="6 Years">6 Years</option>
                                    </select>
                                    @error('period')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="price" class="form-control-label">Price</label>
                                    <input class="form-control" type="number"  name="price" id="price" value="{{ old('price') }}" required>
                                    @error('price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="order" class="form-control-label">Order</label>
                                    <input class="form-control" type="number"  name="order" id="order" value="{{ old('order') }}" required>
                                    @error('order')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-control-label d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" checked>
                                        <label class="form-check-label text-success fw-bold" for="status_active">
                                            <i class="fas fa-check-circle"></i> Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0">
                                        <label class="form-check-label text-danger fw-bold" for="status_inactive">
                                            <i class="fas fa-times-circle"></i> Inactive
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">More Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_en" class="form-control-label">Description (English)</label>
                                        <textarea class="form-control" name="description[en]" id="description_en"></textarea>
                                    </div>
                                    @error('description.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar" class="form-control-label">Description (Arabic)</label>
                                        <textarea class="form-control" name="description[ar]" id="description_ar"></textarea>
                                    </div>
                                    @error('description.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <p class="text-uppercase text-sm">Control</p>
                            <div class="justify-content-center row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-md btn-success w-100 mt-4 mb-0">Add</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a href="{{ route('membership.index')}}" class="btn btn-md btn-danger w-100 mt-4 mb-0">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
