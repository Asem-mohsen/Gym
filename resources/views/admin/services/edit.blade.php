@extends('layout.admin.master')

@section('title', 'Edit ' . $service->name)

@section('main-breadcrumb', 'Service')
@section('main-breadcrumb-link', route('services.index'))

@section('sub-breadcrumb','Edit Service')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('services.update', $service->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="service_en" class="required form-label">Service Name (English)</label>
                    <input type="text" value="{{$service->getTranslation('name','en')}}" id="service_en" name="name[en]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="service_ar" class="required form-label">Service Name (Arabic)</label>
                    <input type="text" value="{{$service->getTranslation('name','ar')}}" id="service_ar" name="name[ar]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="duration" class="required form-label">Duration</label>
                    <input type="text" id="duration" value="{{$service->duration}}" name="duration" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="price" class="required form-label">Price</label>
                    <input type="text" id="price" value="{{$service->price}}" name="price" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_en" class="required form-label">Description (English)</label>
                    <textarea id="description_en" name="description[en]" class="form-control form-control-solid required" required>{{$service->getTranslation('description','en')}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_ar" class="required form-label">Description (Arabic)</label>
                    <textarea id="description_ar" name="description[ar]" class="form-control form-control-solid required" required >{{$service->getTranslation('description','ar')}}</textarea>
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
