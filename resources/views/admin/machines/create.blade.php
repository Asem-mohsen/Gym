@extends('layout.master')

@section('title', 'Add New Machine')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

    <div class="container-fluid py-4">
        <div class="card">
            <form action="{{ route('machines.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary btn-sm mr-2">Add</button>
                                <p class="mb-0"> New Machine</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="code" class="form-control-label">Code</label>
                                        <div class="input-group">
                                            <input class="form-control" id="code" type="text" name="code" value="{{ old('code') }}" required>
                                            <button type="button" class="btn btn-primary remove-phones" onclick="generateUUID()">Generate</button>
                                        </div>
                                    </div>
                                    @error('code')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Machine Name</label>
                                        <input class="form-control" id="name" type="text" name="name" value="{{old('name')}}" required>
                                    </div>
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="form-control-label">Type</label>
                                        <input class="form-control" id="type" type="text" name="type" value="{{old('type')}}" required>
                                    </div>
                                    @error('type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="branch" class="form-control-label">Branch</label>
                                    <select class="select2bs4 form-control" name="branches[]" multiple>
                                        @foreach ($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('branches')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">More Information</p>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description" class="form-control-label">Description</label>
                                        <textarea class="form-control" name="description" id="description" required></textarea>
                                    </div>
                                    @error('description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option hidden selected>Select Status</option>
                                        <option value="available">Available</option>
                                        <option value="in_use">In Use</option>
                                        <option value="under_maintenance">Under Maintenance</option>
                                        <option value="needs_maintenance">Needs Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_maintenance_date" class="form-control-label w-100">Last Maintenance Date</label>
                                        <input class="form-control" id="last_maintenance_date" type="date" name="last_maintenance_date">
                                    </div>
                                    @error('last_maintenance_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="next_maintenance_date" class="form-control-label w-100 text-center">Next Maintenance Date</label>
                                        <input class="form-control" id="next_maintenance_date" type="date" name="next_maintenance_date">
                                    </div>
                                    @error('next_maintenance_date')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="form-control-label">Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*" style="padding: 4px;">
                                    </div>
                                    @error('image')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="justify-content-center row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success w-100 mt-4 mb-0">Add</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a href="{{ route('machines.index') }}"
                                        class="btn btn-danger w-100 mt-4 mb-0">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@section('Js')
    <script src="{{asset( 'assets/plugins/select2/js/select2.full.min.js')}}"></script>
    
    <script>
        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

        function generateUUID() {
            document.getElementById('code').value = crypto.randomUUID();
        }
    </script>
@stop

