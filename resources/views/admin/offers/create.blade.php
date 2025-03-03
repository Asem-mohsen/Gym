@extends('layout.master')

@section('title' , 'Add New Offer')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <div class="container-fluid py-4">
        <form action="{{ route('offers.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-success btn-sm m-2">Add</a>
                                <p class="mb-0">New Offer</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="offer_en" class="form-control-label">Offer Title (English)</label>
                                        <input class="form-control" id="offer_en" type="text" name="title[en]" value="{{old('offer_en')}}" required>
                                    </div>
                                    @error('title.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="offer_ar" class="form-control-label">Offer Title (Arabic)</label>
                                        <input class="form-control" id="offer_ar" type="text" name="title[ar]" value="{{old('offer_ar')}}" required>
                                    </div>
                                    @error('title.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_type" class="form-control-label text-center w-100">Discount Type</label>
                                        <select class="form-control" id="discount_type" name="discount_type">
                                            <option value="fixed">Fixed</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>
                                    @error('discount_type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount_value" class="form-control-label text-center w-100">Discount Value</label>
                                        <input class="form-control" id="discount_value" type="text" name="discount_value" value="{{old('discount_value')}}" required>
                                    </div>
                                    @error('discount_value')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Assigning the Offer</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="assign_to" class="form-control-label">Assign the offer to .. </label>
                                    <select class="select2bs4 w-100 form-control" id="assign_to" name="assign_to[]" multiple="multiple">
                                        <option value="App\Models\Membership">Memberships</option>
                                        <option value="App\Models\Service">Services</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-none" id="membership_container">
                                    <label for="memberships" class="form-control-label" >Select Membership</label>
                                    <select class="select2bs4 form-control" id="memberships" name="memberships[]" multiple="multiple">
                                    </select>
                                </div>
                                <div class="col-md-6 d-none" id="service_container">
                                    <label for="services" class="form-control-label">Select Service</label>
                                    <select class="select2bs4 form-control" id="services" name="services[]" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">More Information</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date" class="form-control-label w-100 text-center">Start date</label>
                                        <input class="form-control" id="start_date" type="date" name="start_date" required>
                                    </div>
                                    @error('start_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date" class="form-control-label w-100 text-center">End date</label>
                                        <input class="form-control" id="end_date" type="date" name="end_date" required>
                                    </div>
                                    @error('end_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_en" class="form-control-label">Description (English)</label>
                                        <textarea class="form-control" name="description[en]" id="description_en" required></textarea>
                                    </div>
                                    @error('description.en')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar" class="form-control-label">Description (Arabic)</label>
                                        <textarea class="form-control" name="description[ar]" id="description_ar"></textarea>
                                    </div>
                                    @error('description.ar')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <p class="text-uppercase text-sm">Control</p>
                            <div class="justify-content-center row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-md btn-success w-100 mt-4 mb-0">Add</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a href="{{ route('offers.index') }}" class="btn btn-md btn-danger w-100 mt-4 mb-0">Cancel</a>
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

@section('Js')
    <script src="{{asset( 'assets/plugins/select2/js/select2.full.min.js')}}"></script>
    
    @include('admin.offers._partials.offers-script')
@stop