@extends('layout.master')

@section('title', 'Edit ' . $service->name)

@section('content')

    <div class="container-fluid py-4">
        <div class="card">
            <form action="{{ route('services.update', $service->id) }}" enctype="multipart/form-data" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary btn-sm mr-2">Edit</button>
                                <p class="mb-0"> Service</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Service" class="form-control-label">Service Name (English)</label>
                                        <input class="form-control" id="Service" type="text" name="name[en]" value="{{$service->getTranslation('name','en')}}" required>
                                    </div>
                                    @error('name.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Service" class="form-control-label">Service Name (Arabic)</label>
                                        <input class="form-control" id="Service" type="text" name="name[ar]" value="{{$service->getTranslation('name','ar')}}" required>
                                    </div>
                                    @error('name.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duration" class="form-control-label">Duration</label>
                                        <input class="form-control" id="duration" type="text" name="duration" value="{{$service->duration}}" required>
                                    </div>
                                    @error('duration')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price" class="form-control-label">Price</label>
                                        <input class="form-control" id="price" type="text" name="price" value="{{$service->price}}" required>
                                    </div>
                                    @error('price')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">More Information</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_en" class="form-control-label">Description (English)</label>
                                        <textarea class="form-control" name="description[en]" id="description_en" required>{{$service->getTranslation('description','en')}}</textarea>
                                    </div>
                                    @error('description.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar" class="form-control-label">Description (Arabic)</label>
                                        <textarea class="form-control" name="description[ar]" id="description_ar">{{$service->getTranslation('description','ar')}}</textarea>
                                    </div>
                                    @error('description.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-control-label">Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*" style="padding: 4px;">
                                    </div>
                                    @error('image')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="justify-content-center row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary w-100 mt-4 mb-0">Update</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a href="{{ route('services.index') }}"
                                        class="btn btn-danger w-100 mt-4 mb-0">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
