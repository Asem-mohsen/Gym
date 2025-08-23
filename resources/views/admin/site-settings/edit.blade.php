@extends('layout.admin.master')

@section('title' , 'Edit Gym Settings')

@section('main-breadcrumb', 'Gym Settings')
@section('main-breadcrumb-link', route('admin.dashboard'))

@section('sub-breadcrumb','Edit Gym Settings')

@section('content')

    <div class="col-md-12 mb-md-5 mb-xl-10">
        <form action="{{ route('site-settings.update') }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-6">
                        <label for="gym_name_en" class="required form-label">Gym Name (English)</label>
                        <input type="text" value="{{ $site->getTranslation('gym_name', 'en') }}" id="gym_name_en" name="gym_name[en]" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="gym_name_ar" class="required form-label">Gym Name (Arabic)</label>
                        <input type="text" value="{{ $site->getTranslation('gym_name', 'ar') }}" id="gym_name_ar" name="gym_name[ar]" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="size" class="required form-label">Number of Employees</label>
                        <input type="text" value="{{ $site->size }}" id="size" name="size" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" value="{{ $site->phone }}" id="phone" name="phone" class="form-control form-control-solid"/>
                    </div>
                    
                    <div class="mb-10 col-md-6">
                        <label for="address_en" class="required form-label">Main address (English)</label>
                        <textarea class="form-control form-control-solid" name="address[en]" id="address_en" required>{{ $site->getTranslation('address', 'en') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="address_ar" class="required form-label">Main address (Arabic)</label>
                        <textarea class="form-control form-control-solid" name="address[ar]" id="address_ar">{{ $site->getTranslation('address', 'ar') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description_en" class="required form-label">Description (English)</label>
                        <textarea class="form-control form-control-solid" name="description[en]" id="description_en">{{ $site->getTranslation('description', 'en') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description_ar" class="required form-label">Description (Arabic)</label>
                        <textarea class="form-control form-control-solid" name="description[ar]" id="description_ar">{{ $site->getTranslation('description', 'ar') }}</textarea>
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="contact_email" class="required form-label">Contact Email</label>
                        <input type="text" value="{{ $site->contact_email }}" id="contact_email" name="contact_email" class="form-control form-control-solid required" required/>
                    </div>

                    <p class="text-uppercase text-sm fw-bold">Media</p>
                    <div class="mb-10 col-md-6">
                        <label for="gym_logo" class="form-label">Gym Logo</label>
                        <input type="file" class="form-control" id="gym_logo" name="gym_logo" accept="image/*">
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="favicon" class="form-label">Fav Icon</label>
                        <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="email_logo" class="form-label">Email Logo</label>
                        <input type="file" class="form-control" id="email_logo" name="email_logo" accept="image/*">
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="footer_logo" class="form-label">Footer Logo</label>
                        <input type="file" class="form-control" id="footer_logo" name="footer_logo" accept="image/*">
                    </div>

                    <p class="text-uppercase text-sm fw-bold">Social Media</p>
                    <div class="mb-10 col-md-4">
                        <label for="facebook_url" class="required form-label">Facebook URL</label>
                        <input type="text" value="{{ $site->facebook_url }}" id="facebook_url" name="facebook_url" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-4">
                        <label for="x_url" class="required form-label">X URL</label>
                        <input type="text" value="{{ $site->x_url }}" id="x_url" name="x_url" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-4">
                        <label for="instagram_url" class="required form-label">Instagram URL</label>
                        <input type="text" value="{{ $site->instagram_url }}" id="instagram_url" name="instagram_url" class="form-control form-control-solid required" required/>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
