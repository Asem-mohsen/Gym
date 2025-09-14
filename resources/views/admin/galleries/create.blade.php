@extends('layout.admin.master')

@section('title' , 'Create Gallery')

@section('main-breadcrumb', 'Gallery Management')
@section('main-breadcrumb-link', route('galleries.index'))

@section('sub-breadcrumb','Create Gallery')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-6">
                        <label for="title" class="required form-label">Gallery Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="content" class="required form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description" class="required form-label">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-solid required" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="is_active" class="required form-label">Status</label>
                        @php
                            $options = [
                                ['value' => '1',  'label' => 'Active'],
                                ['value' => '0', 'label' => 'Inactive'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'is_active',
                            'id' => 'is_active',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="pages" class="required form-label">Display Pages</label>
                        @php
                            $options = [
                                ['value' => 'home', 'label' => 'Home Page'],
                                ['value' => 'about', 'label' => 'About Page'],
                                ['value' => 'services', 'label' => 'Services Page'],
                                ['value' => 'classes', 'label' => 'Classes Page'],
                                ['value' => 'gallery', 'label' => 'Gallery Page'],
                                ['value' => 'contact', 'label' => 'Contact Page'],
                                ['value' => 'branch', 'label' => 'Branch Page'],
                            ];
                        @endphp
                        @include('_partials.select-multiple',[
                            'options' => $options,
                            'name' => 'pages',
                            'id' => 'pages',
                            'values' => old('pages', []),
                        ])
                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple pages</small>
                    </div>

                    <div class="mb-10 col-md-6">
                        <div class="form-group">
                            <label for="gallery_images" class="form-control-label required">Upload Images</label>
                            <input class="form-control form-control-solid" type="file" name="gallery_images[]" multiple accept="image/*" required>
                        </div>
                    </div>

                    <div class="card-footer">
                        @can('create_gallery')
                            <button type="submit" class="btn btn-success">Save</button>
                        @endcan
                        <a href="{{ route('galleries.index') }}" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    @include('admin.galleries.assets.scripts')
@stop
