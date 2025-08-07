@extends('layout.admin.master')

@section('title', 'Add Role')

@section('main-breadcrumb', 'Role')
@section('main-breadcrumb-link', route('roles.index'))

@section('sub-breadcrumb','Create Role')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ Route('roles.store') }}" method="post">
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">Role Name</label>
                    @php
                        $options = [
                            ['value' => 'admin', 'label' => 'Admin'],
                            ['value' => 'user', 'label' => 'User'],
                            ['value' => 'trainer', 'label' => 'Trainer'],
                            ['value' => 'coach', 'label' => 'Coach'],
                            ['value' => 'staff', 'label' => 'Staff'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'name',
                        'id' => 'name',
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control form-control-solid">{{ old('description') }}</textarea>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
    
@endsection
