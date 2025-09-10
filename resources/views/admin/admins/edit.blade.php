@extends('layout.admin.master')

@section('title' , 'Edit Admin')

@section('title','Edit admin')
@section('page-title', 'Edit admin')

@section('main-breadcrumb', 'Admin')
@section('main-breadcrumb-link', route('admins.index'))

@section('sub-breadcrumb','Edit Admin')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('admins.update',$admin->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">Name</label>
                    <input type="text" name="name" value="{{$admin->name}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="email" class="required form-label">Email address</label>
                    <input type="email" name="email" value="{{$admin->email}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="role_ids" class="required form-label">Role</label>
                    @php
                        $options = [];
                        foreach($roles as $role){
                            $options[] = [
                                'value' => $role['id'],
                                'label' => $role['name']
                            ];
                        }
                        $selectedRoles = $admin->roles->pluck('id')->toArray();
                    @endphp
                    @include('_partials.select-multiple',[
                        'options' => $options,
                        'name' => 'role_ids',
                        'values' => old('role_ids', $selectedRoles),
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="branch_ids" class="required form-label">Assigned Branches</label>
                    @php
                        $branchOptions = [];
                        foreach($branches as $branch){
                            $branchOptions[] = [
                                'value' => $branch['id'],
                                'label' => $branch['name']
                            ];
                        }
                        $selectedBranches = old('branch_ids', $admin->assignedBranches->pluck('id')->toArray());
                    @endphp
                    @include('_partials.select-multiple',[
                        'options' => $branchOptions,
                        'name' => 'branch_ids',
                        'id' => 'branch_ids',
                        'values' => $selectedBranches,
                    ])
                </div>   
                <div class="mb-10 col-md-6">
                    <label for="gender" class="required form-label">Gender</label>
                    @php
                        $options = [
                            ['value' => 'male', 'label' => 'Male'],
                            ['value' => 'female', 'label' => 'Female'],
                        ];
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'gender',
                        'id' => 'gender',
                        'selectedValue' => $admin->gender
                    ])
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
                        'selectedValue' => $admin->status
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="phone" class="required form-label">Phone</label>
                    <input type="text" value="{{$admin->phone}}" name="phone" class="form-control form-control-solid required" required/>
                </div>  
                <div class="mb-10 col-md-6">
                    <label for="address" class="required form-label">Address</label>
                    <textarea name="address" class="form-control form-control-solid required" required >{{$admin->address}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="country" class="required form-label">Country</label>
                    <input type="text" value="{{ old('country', $admin->country) }}" value="Egypt" placeholder="Egypt" name="country" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="city" class="required form-label">City</label>
                    <input type="text" value="{{ old('city', $admin->city) }}" name="city" class="form-control form-control-solid"/>
                </div>
                        
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('admins.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
