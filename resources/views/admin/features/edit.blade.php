@extends('layout.admin.master')

@section('title','Edit Feature')

@section('main-breadcrumb', 'Features')
@section('main-breadcrumb-link', route('features.index'))

@section('sub-breadcrumb','Edit Feature')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('features.update', $feature->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name_en" class="required form-label">Feature Name (English)</label>
                    <input type="text" name="name[en]" id="name_en" value="{{ old('name.en', $feature->getTranslation('name', 'en')) }}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="name_ar" class="required form-label">Feature Name (Arabic)</label>
                    <input type="text" name="name[ar]" id="name_ar" value="{{ old('name.ar', $feature->getTranslation('name', 'ar')) }}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="order" class="required form-label">Order</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $feature->order) }}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="status" class="required form-label">Status</label>
                    @php
                        $options = [
                            ['value' => 1, 'label' => 'Active'],
                            ['value' => 0, 'label' => 'Inactive'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'status',
                        'id' => 'status',
                        'selectedValue' => $feature->status,
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_en" class="required form-label">Description (English)</label>
                    <textarea name="description[en]" id="description_en" class="form-control form-control-solid required" required>{{ old('description.en', $feature->getTranslation('description', 'en')) }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_ar" class="required form-label">Description (Arabic)</label>
                    <textarea name="description[ar]" id="description_ar" class="form-control form-control-solid required" required>{{ old('description.ar', $feature->getTranslation('description', 'ar')) }}</textarea>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('features.index') }}" class="btn btn-dark">Cancel</a>
            </div>
        </div>
    </form>
</div>

@endsection 