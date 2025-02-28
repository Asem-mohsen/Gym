@extends('layout.master')

@section('title' , 'Edit')

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

            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <form action="{{ route('membership.update',$membership->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary btn-sm ms-auto m-2">Edit</button>
                                <p class="mb-0">{{$membership->name}}</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">{{$membership->name}} Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_en" class="form-control-label">Membership Name (English)</label>
                                        <input class="form-control" type="text" name="name[en]" id="name_en" value="{{$membership->getTranslation('name','en')}}" required>
                                    </div>
                                    @error('name.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_ar" class="form-control-label">Membership Name (Arabic)</label>
                                        <input class="form-control" type="text" name="name[ar]" id="name_ar" value="{{$membership->getTranslation('name','ar')}}" required>
                                    </div>
                                    @error('name.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="period" class="form-control-label">Period</label>
                                    <select class="form-control" name="period" id="period">
                                        <option @selected($membership->period) value="{{ $membership->period }}">{{ $membership->period }}</option>
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
                                    <input class="form-control" type="number"  name="price" id="price" value="{{ $membership->price }}" required>
                                    @error('price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="order" class="form-control-label">Order</label>
                                    <input class="form-control" type="number"  name="order" id="order" value="{{ $membership->order }}" required>
                                    @error('order')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-control-label d-block">Status</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_active" value="1" 
                                            @checked(isset($membership) && $membership->status == 1)>
                                        <label class="form-check-label text-success fw-bold" for="status_active">
                                            <i class="fas fa-check-circle"></i> Active
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0"
                                            @checked(isset($membership) && $membership->status == 0)>
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
                                        <textarea class="form-control" name="description[en]" id="description_en">{{$membership->getTranslation('description','en')}}</textarea>
                                    </div>
                                    @error('description.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar" class="form-control-label">Description (Arabic)</label>
                                        <textarea class="form-control" name="description[ar]" id="description_ar">{{$membership->getTranslation('description','en')}}</textarea>
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
                                        <button type="submit" class="btn btn-md btn-success w-100 mt-4 mb-0">Update</button>
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
