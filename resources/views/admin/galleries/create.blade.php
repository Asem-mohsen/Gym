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
                                ['value' => 'active',  'label' => 'Active'],
                                ['value' => 'inactive', 'label' => 'Inactive'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'is_active',
                            'id' => 'is_active',
                        ])
                    </div>

                    <div class="mb-10 col-md-12">
                        <div class="form-group">
                            <label for="gallery_images" class="form-control-label">Upload Images *</label>
                            <input class="form-control form-control-solid" type="file" name="gallery_images[]" multiple accept="image/*" required>
                        </div>
                    </div>

                    <div class="card-footer">
                        @can('create_galleries')
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
<script>
    document.querySelector('input[name="gallery_images[]"]').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.createElement('div');
        previewContainer.className = 'row mt-3';
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-3';
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted">${file.name}</small>
                            </div>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        }
        
        const existingPreview = document.querySelector('.image-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
        
        previewContainer.className += ' image-preview';
        document.querySelector('.form-group').appendChild(previewContainer);
    });
</script>
@stop
