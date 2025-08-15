@extends('layout.admin.master')

@section('title' , 'Edit')

@section('main-breadcrumb', 'Blog Posts')
@section('main-breadcrumb-link', route('blog-posts.index'))

@section('sub-breadcrumb','Edit Blog Post')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <form action="{{ route('blog-posts.update', $blogPost->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-12">
                        <label for="title" class="required form-label">Title</label>
                        <input type="text" name="title" id="title" value="{{ $blogPost->title }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="content" class="required form-label">Main Content</label>
                        <textarea name="content" id="content" class="form-control form-control-solid required" required>{{ $blogPost->content ?? old('content') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description" class="required form-label">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-solid required" required>{{ $blogPost->description ?? old('description') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-4">
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
                        <select class="form-select form-select-solid" name="categories[]" id="categories" data-control="select2" data-placeholder="Select or create a category" data-allow-clear="true" data-tags="true" multiple>
                            <option></option>
                            @foreach($options as $option)
                                <option value="{{ $option['value'] }}" {{ $blogPost->categories->contains($option['value']) ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-10 col-md-4">
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
                        <select class="form-select form-select-solid" name="tags[]" id="tags" data-control="select2" data-placeholder="Select or create a tag" data-allow-clear="true" data-tags="true" multiple>
                            <option></option>
                            @foreach($options as $option)
                                <option value="{{ $option['value'] }}" {{ $blogPost->tags->contains($option['value']) ? 'selected' : '' }}>
                                    {{ $option['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-10 col-md-4">
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
                            'selectedValue' => $blogPost->status,
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="images" class="form-label">Other Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple>
                    </div>

                    <div class="mb-10 col-md-6">
                        <label for="quote_author_name" class="required form-label">Quote Author Name</label>
                        <input type="text" name="quote_author_name" id="quote_author_name" value="{{ $blogPost->quote_author_name ?? old('quote_author_name') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="quote_author_title" class="required form-label">Quote Author Title</label>
                        <input type="text" name="quote_author_title" id="quote_author_title" value="{{ $blogPost->quote_author_title ?? old('quote_author_title') }}" class="form-control form-control-solid required" required/>
                    </div>

                    <div class="mb-10 col-md-12">
                        <label for="author_comment" class="required form-label">Author Comment</label>
                        <textarea name="author_comment" id="author_comment" class="form-control form-control-solid required" rows="6" required>{{ $blogPost->author_comment ?? old('author_comment') }}</textarea>
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
