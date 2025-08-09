@extends('layout.admin.master')

@section('title','Create Membership')

@section('main-breadcrumb', 'Membership')
@section('main-breadcrumb-link', route('membership.index'))

@section('sub-breadcrumb','Create Membership')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('membership.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-6">
                        <label for="name_en" class="required form-label">Membership Name (English)</label>
                        <input type="text" name="name[en]" id="name_en" value="{{ old('name.en') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="name_ar" class="required form-label">Membership Name (Arabic)</label>
                        <input type="text" name="name[ar]" id="name_ar" value="{{ old('name.ar') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="period" class="required form-label">Period</label>
                        @php
                            $options = [
                                ['value' => 'Month',   'label' => 'Month'],
                                ['value' => '3 Month', 'label' => '3 Month'],
                                ['value' => '6 Month', 'label' => '6 Month'],
                                ['value' => 'Year',    'label' => 'Year'],
                                ['value' => '2 Years', 'label' => '2 Years'],
                                ['value' => '3 Years', 'label' => '3 Years'],
                                ['value' => '4 Years', 'label' => '4 Years'],
                                ['value' => '6 Years', 'label' => '6 Years'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'period',
                            'id' => 'period',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="price" class="required form-label">Price</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="order" class="required form-label">Order</label>
                        <input type="number" name="order" id="order" value="{{ old('order') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="status" class="required form-label">Status</label>
                        @php
                            $options = [
                                ['value' => '1', 'label' => 'Active'],
                                ['value' => '0', 'label' => 'Inactive'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'status',
                            'id' => 'status',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="features" class="form-label form-control-solid">Features</label>
                        @php
                            $options = [];
                            foreach($features as $feature){
                                $options[] = [
                                    'value' => $feature['id'],
                                    'label' => $feature['name']
                                ];
                            }
                        @endphp
                        @include('_partials.select-multiple',[
                            'options' => $options,
                            'name' => 'features',
                            'id' => 'features',
                            'values' => old('features', []),
                            'notRequired' => true,
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description_en" class="required form-label">Description (English)</label>
                        <textarea name="description[en]" id="description_en" class="form-control form-control-solid required" required>{{ old('description.en') }}</textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description_ar" class="required form-label">Description (Arabic)</label>
                        <textarea name="description[ar]" id="description_ar" class="form-control form-control-solid required" required>{{ old('description.ar') }}</textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('membership.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
