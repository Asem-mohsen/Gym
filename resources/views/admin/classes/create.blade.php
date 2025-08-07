@extends('layout.admin.master')

@section('title', 'Create Class')

@section('main-breadcrumb', 'Classes')
@section('main-breadcrumb-link', route('classes.index'))

@section('sub-breadcrumb','Create Class')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <div class="card">
        <form action="{{ route('classes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body row">
                    <div class="mb-10 col-md-6">
                        <label for="name" class="required form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control form-control-solid required" required/>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="type" class="required form-label">Type</label>
                        @php
                            $options = [
                                ['value' => 'cardio',  'label' => 'Cardio'],
                                ['value' => 'strength', 'label' => 'Strength'],
                                ['value' => 'power', 'label' => 'Power'],
                                ['value' => 'zomba',    'label' => 'Zomba'],
                                ['value' => 'yoga', 'label' => 'Yoga'],
                                ['value' => 'pilate', 'label' => 'Pilate'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'type',
                            'id' => 'type',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="status" class="required form-label">Status</label>
                        @php
                            $options = [
                                ['value' => 'active',  'label' => 'Active'],
                                ['value' => 'inactive', 'label' => 'Inactive'],
                            ];
                        @endphp
                        @include('_partials.select',[
                            'options' => $options,
                            'name' => 'status',
                            'id' => 'status',
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="trainers" class="form-label">trainers</label>
                        @php
                            $options = [];
                            foreach($trainers as $trainer){
                                $options[] = [
                                    'value' => $trainer->id,
                                    'label' => $trainer->name
                                ];
                            }
                        @endphp
                        @include('_partials.select-multiple',[
                            'options' => $options,
                            'name' => 'trainers',
                            'notRequired' => true,
                        ])
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="description" class="required form-label">Description</label>
                        <textarea name="description" id="description" class="form-control form-control-solid required" required>{{ old('name') }} </textarea>
                    </div>
                    <div class="mb-10 col-md-6">
                        <label for="image" class="required form-label">Upload Image</label>
                        <input type="file" name="image" id="image" class="form-control form-control-solid"/>
                    </div>
                    <div class="mb-10 col-md-12">
                        <label class="required form-label">Schedules</label>
                        <div id="schedules-repeater" data-kt-repeater="list">
                            <div data-repeater-list="schedules">
                                <div data-repeater-item class="form-group row mb-3">
                                    <div class="col-md-3">
                                        <select name="day" class="form-control form-control-solid required">
                                            <option value="sunday">Sunday</option>
                                            <option value="monday">Monday</option>
                                            <option value="tuesday">Tuesday</option>
                                            <option value="wednesday">Wednesday</option>
                                            <option value="thursday">Thursday</option>
                                            <option value="friday">Friday</option>
                                            <option value="saturday">Saturday</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="time" name="start_time" class="form-control form-control-solid" placeholder="Start Time" required/>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="time" name="end_time" class="form-control form-control-solid" placeholder="End Time" required/>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                            <i class="ki-duotone ki-cross fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" data-repeater-create class="btn btn-light-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Add Schedule
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10 col-md-12">
                        <label class="required form-label">Pricing</label>
                        <div id="pricing-repeater" data-kt-repeater="list">
                            <div data-repeater-list="pricings">
                                <div data-repeater-item class="form-group row mb-3">
                                    <div class="col-md-4">
                                        <input type="number" name="price" class="form-control form-control-solid required" placeholder="Price" step="0.01" required/>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="duration" class="form-control form-control-solid required" placeholder="Duration (e.g. per session)" required/>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" data-repeater-delete class="btn btn-md btn-light-danger">
                                            <i class="ki-duotone ki-cross fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" data-repeater-create class="btn btn-light-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Add Pricing
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('classes.index') }}" class="btn btn-dark">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection 

@section('js')

    <script src="{{ asset('assets/admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>

    <script>
        $('#schedules-repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $('#pricing-repeater').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    </script>
@stop
