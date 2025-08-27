@extends('layout.admin.master')

@section('title' , 'Edit ' . $branch->name)

@section('main-breadcrumb', 'Branch')
@section('main-breadcrumb-link', route('branches.index'))

@section('sub-breadcrumb','Edit Branch')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('branches.update' , $branch->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name_en" class="required form-label">Branch Name (English)</label>
                    <input type="text" name="name[en]" id="name_en"  value="{{$branch->getTranslation('name','en')}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="service_ar" class="required form-label">Branch Name (Arabic)</label>
                    <input type="text" name="name[ar]" id="name_ar"  value="{{$branch->getTranslation('name','ar')}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="manager_id" class="required form-label">Manager</label>
                    @php
                        $options = [];
                        foreach($users as $id => $user){
                            $options[] = [
                                'value' => $id,
                                'label' => $user->name
                            ];
                        }
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'manager_id',
                        'id' => 'manager_id',
                        'selectedValue' => $branch->manager_id
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="type" class="required form-label">Type</label>
                    @php
                        $options = [
                            ['value' => 'mix', 'label' => 'Mix'],
                            ['value' => 'ladies', 'label' => 'Ladies'],
                            ['value' => 'men', 'label' => 'Men'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'type',
                        'id' => 'type',
                        'selectedValue' => $branch->type
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="location_en" class="required form-label">Location (English)</label>
                    <textarea class="form-control form-control-solid required" name="location[en]" id="location_en" required>{{$branch->getTranslation('location','en')}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="location_ar" class="required form-label">Location (Arabic)</label>
                    <textarea class="form-control form-control-solid required" name="location[ar]" id="location_ar" required>{{$branch->getTranslation('location','ar')}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="image" name="image" class="form-control form-control-solid" accept="image/*"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="size" class="required form-label">Size</label>
                    <input type="size" id="size" value="{{ $branch->size }}" name="size" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="facebook_url" class="required form-label">Facebook URL</label>
                    <input type="text" id="facebook_url" value="{{ $branch->facebook_url }}" name="facebook_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="instagram_url" class="form-label">Instagram URL</label>
                    <input type="text" id="instagram_url" value="{{ $branch->instagram_url }}" name="instagram_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-4">
                    <label for="x_url" class="form-label">X URL</label>
                    <input type="text" id="x_url" value="{{ $branch->x_url }}" name="x_url" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label class="required form-label">Phone Numbers</label>
                    <div id="phone-repeater" data-kt-repeater="list">
                        <div data-repeater-list="phones">
                            @if($existingPhones && count($existingPhones) > 0)
                                @foreach($existingPhones as $index => $phone)
                                    <div data-repeater-item class="form-group row mb-3">
                                        <div class="col-md-10">
                                            <input type="text" name="phones" class="form-control form-control-solid required" placeholder="Phone Number" value="{{ $phone }}" required/>
                                        </div>
                                        <div class="col-md-2">
                                            @if($index > 0)
                                                <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div data-repeater-item class="form-group row mb-3">
                                    <div class="col-md-10">
                                        <input type="text" name="phones" class="form-control form-control-solid required" placeholder="Phone Number" required/>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="button" data-repeater-create class="btn btn-light-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                Add Phone
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-10 col-md-6">
                    <label for="map_url" class="form-label">Map URL</label>
                    <input type="url" id="map_url" value="{{ $branch->map_url }}" name="map_url" class="form-control form-control-solid" placeholder="https://maps.google.com/..."/>
                    <small class="form-text text-muted">Share the Google Maps or any map service URL for the branch location</small>
                </div>

                <div class="card-footer">
                    @can('edit_branches')
                        <button type="submit" class="btn btn-success">Save</button>
                    @endcan
                    <a href="{{ route('branches.index') }}" class="btn btn-dark">Cancel</a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script>
        $('#phone-repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
@endsection