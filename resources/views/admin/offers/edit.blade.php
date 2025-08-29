@extends('layout.admin.master')

@section('title' , 'Edit Offer')

@section('main-breadcrumb', 'Offers')
@section('main-breadcrumb-link', route('offers.index'))

@section('sub-breadcrumb','Edit Offer')

@section('content')

 <div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="title_en" class="required form-label">Title (English)</label>
                    <input type="text" value="{{ $offer->getTranslation('title', 'en') }}" id="title_en" name="title[en]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="title_ar" class="required form-label">Title (Arabic)</label>
                    <input type="text" value="{{ $offer->getTranslation('title', 'ar') }}" id="title_ar" name="title[ar]" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="discount_type" class="required form-label">Discount Type</label>
                    @php
                        $options = [
                            ['value' => 'fixed', 'label' => 'Fixed'],
                            ['value' => 'percentage', 'label' => 'Percentage'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'discount_type',
                        'id' => 'discount_type',
                        'selectedValue' => $offer->discount_type,
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="discount_value" class="required form-label">Discount Value</label>
                    <input type="text" value="{{ $offer->discount_value }}" id="discount_value" name="discount_value" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="assign_to" class="required form-label">Assign Offer To</label>
                    @php
                        $options = [
                            ['value' => 'App\Models\Membership', 'label' => 'Memberships'],
                            ['value' => 'App\Models\Service', 'label' => 'Services'],
                        ];
                    @endphp
                     @include('_partials.select-multiple',[
                        'options' => $options,
                        'name' => 'assign_to',
                        'id' => 'assign_to',
                        'notRequired' => true,
                        'values' => $assignedModels,
                    ])
                </div>
                <div class="mb-10 col-md-6 d-none" id="membership_container">
                    <label for="memberships" class="required form-label">Select Membership</label>
                     @include('_partials.select-multiple',[
                        'options' => array_merge(
                            [['value' => 'all', 'label' => 'All Memberships']],
                            $memberships->map(function($membership) {
                                return ['value' => $membership->id, 'label' => $membership->getTranslation('name', app()->getLocale())];
                            })->toArray()
                        ),
                        'name' => 'memberships',
                        'id' => 'memberships',
                        'notRequired' => true,
                        'values' => $allMembershipsSelected ? ['all'] : $selectedMemberships,
                    ])
                </div>
                <div class="mb-10 col-md-6 d-none" id="service_container">
                    <label for="services" class="required form-label">Select Service</label>
                     @include('_partials.select-multiple',[
                        'options' => array_merge(
                            [['value' => 'all', 'label' => 'All Services']],
                            $services->map(function($service) {
                                return ['value' => $service->id, 'label' => $service->getTranslation('name', app()->getLocale())];
                            })->toArray()
                        ),
                        'name' => 'services',
                        'id' => 'services',
                        'notRequired' => true,
                        'values' => $allServicesSelected ? ['all'] : $selectedServices,
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="start_date" class="required form-label">Start date</label>
                    <input type="date" value="{{ $offer->start_date }}" id="start_date" name="start_date" class="form-control form-control-solid" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="end_date" class="required form-label">End date</label>
                    <input type="date" value="{{ $offer->end_date }}" id="end_date" name="end_date" class="form-control form-control-solid" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_en" class="required form-label">Description (English)</label>
                    <textarea class="form-control form-control-solid" name="description[en]" id="description_en" required>{{ $offer->getTranslation('description', 'en') }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description_ar" class="required form-label">Description (Arabic)</label>
                    <textarea class="form-control form-control-solid" name="description[ar]" id="description_ar">{{ $offer->getTranslation('description', 'ar') }}</textarea>
                </div>
                <div class="card-footer">
                    @can('edit_offers')
                        <button type="submit" class="btn btn-success">Save</button>
                    @endcan
                    <a href="{{ route('offers.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')    
    @include('admin.offers._partials.offers-script')
@stop