@extends('layout.master')

@section('title', 'Edit Gym Settings')

@section('content')

    <div class="container-fluid py-4">
        <div class="card">
            <form action="{{ route('site-settings.update', $site->id) }}" enctype="multipart/form-data" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary btn-sm mr-2">Edit</button>
                                <p class="mb-0"> Gym Settings</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gym_name_en" class="form-control-label">Gym Name (English)</label>
                                        <input class="form-control" id="gym_name_en" type="text" name="gym_name[en]" value="{{$site->getTranslation('gym_name','en')}}" required>
                                    </div>
                                    @error('gym_name.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gym_name_ar" class="form-control-label">Gym Name (Arabic)</label>
                                        <input class="form-control" id="gym_name_ar" type="text" name="gym_name[ar]" value="{{$site->getTranslation('gym_name','ar')}}" required>
                                    </div>
                                    @error('gym_name.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="size" class="form-control-label">Number of Employees</label>
                                        <input class="form-control" id="size" type="text" name="size" value="{{$site->size}}" required>
                                    </div>
                                    @error('size')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">More Information</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_en" class="form-control-label">Main address (English)</label>
                                        <textarea class="form-control" name="address[en]" id="address_en" required>{{$site->getTranslation('address','en')}}</textarea>
                                    </div>
                                    @error('address.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_ar" class="form-control-label">Main address (Arabic)</label>
                                        <textarea class="form-control" name="address[ar]" id="address_ar" required>{{$site->getTranslation('address','ar')}}</textarea>
                                    </div>
                                    @error('address.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_en" class="form-control-label">Description (English)</label>
                                        <textarea class="form-control" name="description[en]" id="description_en">{{$site->getTranslation('description','en')}}</textarea>
                                    </div>
                                    @error('description.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar" class="form-control-label">Description (Arabic)</label>
                                        <textarea class="form-control" name="description[ar]" id="description_ar">{{$site->getTranslation('description','ar')}}</textarea>
                                    </div>
                                    @error('description.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Emails</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_email" class="form-control-label">Contact Email</label>
                                        <input class="form-control" id="contact_email" type="text" name="contact_email" value="{{$site->contact_email}}" required>
                                    </div>
                                    @error('contact_email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Media</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gym_logo" class="form-control-label">Gym Logo</label>
                                        <input type="file" class="form-control" id="gym_logo" name="gym_logo" accept="image/*" style="padding: 4px;">
                                    </div>
                                    @error('gym_logo') <div class="alert alert-danger">{{ $message }}</div> @enderror
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="favicon" class="form-control-label">Fav Icon</label>
                                        <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*" style="padding: 4px;">
                                    </div>
                                    @error('favicon') <div class="alert alert-danger">{{ $message }}</div> @enderror
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email_logo" class="form-control-label">Email Logo</label>
                                        <input type="file" class="form-control" id="email_logo" name="email_logo" accept="image/*" style="padding: 4px;">
                                    </div>
                                    @error('email_logo') <div class="alert alert-danger">{{ $message }}</div> @enderror
                                </div>
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="footer_logo" class="form-control-label">Footer Logo</label>
                                        <input type="file" class="form-control" id="footer_logo" name="footer_logo" accept="image/*" style="padding: 4px;">
                                    </div>
                                    @error('footer_logo') <div class="alert alert-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="justify-content-center row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success w-100 mt-4 mb-0">Update</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger w-100 mt-4 mb-0">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
