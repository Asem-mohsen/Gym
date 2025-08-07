@extends('layout.admin.master')

@section('title' , 'Add Blog Post')

@section('main-breadcrumb', 'Blog Posts')
@section('main-breadcrumb-link', route('blog-posts.index'))

@section('sub-breadcrumb','Create Blog Post')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <form action="{{ route('blog-posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-6">
                        <label for="title" class="required form-label">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="content" class="required form-label">Content</label>
                        <textarea name="content" id="content" class="form-control form-control-solid required" required>{{ old('content') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="categories" class="form-label">Categories</label>
                        @php
                            $options = [];
                            foreach($categories as $category){
                                $options[] = [
                                    'value' => $category->id,
                                    'label' => $category->name
                                ];
                            }
                        @endphp
                        <select class="form-select form-select-solid" name="categories" id="categories" data-control="select2" data-placeholder="Select or create a category" data-allow-clear="true" data-tags="true" multiple>
                            <option></option>
                            @foreach($options as $option)
                                <option value="{{ $option['value'] }}" {{ old('categories') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="tags" class="form-label">Tags</label>
                        @php
                            $options = [];
                            foreach($tags as $tag){
                                $options[] = [
                                    'value' => $tag->id,
                                    'label' => $tag->name
                                ];
                            }
                        @endphp
                        <select class="form-select form-select-solid" name="tags" id="tags" data-control="select2" data-placeholder="Select or create a tag" data-allow-clear="true" data-tags="true" multiple>
                            <option></option>
                            @foreach($options as $option)
                                <option value="{{ $option['value'] }}" {{ old('tags') == $option['value'] ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="status" class="required form-label">Status</label>
                        @php
                            $options = [
                                ['value' => 'draft',  'label' => 'Draft'],
                                ['value' => 'published', 'label' => 'Published'],
                                ['value' => 'archived', 'label' => 'Archived'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'status',
                            'id' => 'status',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="image" class="required form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('blog-posts.index') }}" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection