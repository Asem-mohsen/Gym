@extends('layout.admin.master')

@section('title', 'Add Service')

@section('main-breadcrumb', 'Service')
@section('main-breadcrumb-link', route('services.index'))

@section('sub-breadcrumb','Create Service')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('services.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="service_en" class="required form-label">Service Name (English)</label>
                    <input type="text" value="{{ old('name_en') }}" id="service_en" name="name[en]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="service_ar" class="required form-label">Service Name (Arabic)</label>
                    <input type="text" value="{{ old('name_ar') }}" id="service_ar" name="name[ar]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="duration" class="required form-label">Duration</label>
                    <input type="text" id="duration" value="{{ old('duration') }}" name="duration" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="price" class="required form-label">Price</label>
                    <input type="text" id="price" value="{{ old('price') }}" name="price" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_en" class="required form-label">Description (English)</label>
                    <textarea id="description_en" name="description[en]" class="form-control form-control-solid required" required>{{ old('description_en') }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_ar" class="required form-label">Description (Arabic)</label>
                    <textarea id="description_ar" name="description[ar]" class="form-control form-control-solid required" required >{{ old('description_ar') }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="image" class="required form-label">Image</label>
                    <input type="file" id="image" name="image" class="form-control form-control-solid required" accept="image/*" required/>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('services.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
