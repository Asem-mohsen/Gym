@extends('layout.admin.master')

@section('title', 'Edit Staff')

@section('main-breadcrumb', 'Management')
@section('main-breadcrumb-link', '#')

@section('sub-breadcrumb', 'Edit Staff')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route('staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">Name</label>
                    <input type="text" value="{{ old('name', $staff->name) }}" name="name" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="email" class="required form-label">Email address</label>
                    <input type="email" value="{{ old('email', $staff->email) }}" name="email" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="role_ids" class="required form-label">Roles</label>
                    @php
                        $options = [];
                        foreach($staffRoles as $role){
                            $options[] = [
                                'value' => $role['id'],
                                'label' => $role['name']
                            ];
                        }
                    @endphp
                    @include('_partials.select-multiple',[
                        'options' => $options,
                        'name' => 'role_ids',
                        'values' => old('role_ids', $staff->roles->pluck('id')->toArray()),
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
                        'selectedValue' => old('gender', $staff->gender),
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
                        'selectedValue' => old('status', $staff->status),
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
                        $selectedBranches = old('branch_ids', $staff->assignedBranches->pluck('id')->toArray());
                    @endphp
                    @include('_partials.select-multiple',[
                        'options' => $branchOptions,
                        'name' => 'branch_ids',
                        'id' => 'branch_ids',
                        'values' => $selectedBranches,
                    ])
                </div>   
                <div class="mb-10 col-md-6">
                    <label for="phone" class="required form-label">Phone</label>
                    <input type="text" value="{{ old('phone', $staff->phone) }}" name="phone" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="address" class="required form-label">Address</label>
                    <textarea name="address" class="form-control form-control-solid required" required >{{ old('address', $staff->address) }}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="country" class="required form-label">Country</label>
                    <input type="text" value="{{ old('country', $staff->country) }}" value="Egypt" placeholder="Egypt" name="country" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="city" class="required form-label">City</label>
                    <input type="text" value="{{ old('city', $staff->city) }}" name="city" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="image" class="form-label">Staff Image</label>
                    @if($staff->getFirstMediaUrl('user_images'))
                        <div class="mb-2">
                            <img src="{{ $staff->getFirstMediaUrl('user_images') }}" alt="Current Profile Image" class="img-thumbnail" style="max-width: 150px;">
                            <p class="text-muted small">Current profile image</p>
                        </div>
                    @endif
                    <input class="form-control" type="file" name="image" accept="image/*">
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('staff.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection