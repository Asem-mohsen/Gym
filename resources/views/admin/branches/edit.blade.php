@extends('layout.master')

@section('title' , 'Edit ' . $branch->name)

@section('content')

    <div class="container-fluid py-4">
        <form id="productForm" action="{{ route('branches.update' , $branch->id) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary btn-sm ms-auto m-2">Edit</button>
                                <p class="mb-0">Branch</p>
                            </div>
                        </div>
                        <div class="card-body">

                            <p class="text-uppercase text-sm">Branch Information</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_en" class="form-control-label">Branch Name (English)</label>
                                        <input class="form-control" type="text" name="name[en]" id="name_en" value="{{$branch->getTranslation('name','en')}}" required>
                                    </div>
                                    @error('name.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_ar" class="form-control-label">Branch Name (Arabic)</label>
                                        <input class="form-control" type="text" name="name[ar]" id="name_ar" value="{{$branch->getTranslation('name','ar')}}" required>
                                    </div>
                                    @error('name.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="manager_id" class="form-control-label">Manager</label>
                                    <select class="form-control" name="manager_id" id="manager_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == $branch->manager_id) >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('manager_id')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="type" class="form-control-label">Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option selected hidden disabled>Select type</option>
                                        <option value="mix" {{ $branch->type == 'mix' ? 'selected' : '' }}>Mix</option>
                                        <option value="ladies" {{ $branch->type == 'ladies' ? 'selected' : '' }}>ladies</option>
                                        <option value="men" {{ $branch->type == 'men' ? 'selected' : '' }}>men</option>
                                    </select>
                                    @error('type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location_en" class="form-control-label">Location (English)</label>
                                        <textarea class="form-control" name="location[en]" id="location_en" required>{{ $branch->getTranslation('location','en') }}</textarea>
                                    </div>
                                    @error('location.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location_ar" class="form-control-label">Location (Arabic)</label>
                                        <textarea class="form-control" name="location[ar]" id="location_ar" required>{{ $branch->getTranslation('location','en') }}</textarea>
                                    </div>
                                    @error('location.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">More Information</p>

                            @livewire('phone-repeater', ['existingPhones' => $existingPhones])
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size" class="form-control-label">Size</label>
                                        <input class="form-control" name="size" type="text" value="{{ $branch->size }}" id="size">
                                    </div>
                                    @error('size')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook_url" class="form-control-label">Facebook URL</label>
                                        <input class="form-control" name="facebook_url" type="text" value="{{ $branch->facebook_url }}" id="facebook_url">
                                    </div>
                                    @error('facebook_url')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="instagram_url" class="form-control-label">Instagram URL</label>
                                        <input class="form-control" name="instagram_url" type="text" value="{{ $branch->instagram_url }}" id="instagram_url">
                                    </div>
                                    @error('instagram_url')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="x_url" class="form-control-label">X URL</label>
                                        <input class="form-control" name="x_url" type="text" value="{{ $branch->x_url }}" id="x_url">
                                    </div>
                                    @error('x_url')
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
                                        <a href="{{ route('branches.index')}}" class="btn btn-md btn-danger w-100 mt-4 mb-0">Cancel</a>
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
