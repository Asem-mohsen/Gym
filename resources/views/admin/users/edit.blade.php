@extends('layout.admin.master')

@section('title' , 'Edit User')

@section('main-breadcrumb', 'User')
@section('main-breadcrumb-link', route('users.index'))

@section('sub-breadcrumb','Edit User')

@section('content')

<div class="col-md-12 mb-md-5 mb-xl-10">

    <form action="{{ route('users.update',$user->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">Name</label>
                    <input type="text" name="name" value="{{$user->name}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="email" class="required form-label">Email address</label>
                    <input type="email" name="email" value="{{$user->email}}" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="password" class="required form-label">Password</label>
                    <input type="password" value="{{ old('password') }}" name="password" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="role_id" class="required form-label">Role</label>
                    @php
                        $options = [];
                        foreach($roles as $id => $role){
                            $options[] = [
                                'value' => $id,
                                'label' => $role->name
                            ];
                        }
                    @endphp
                    @include('_partials.select',[
                        'options' => $options,
                        'name' => 'role_id',
                        'id' => 'role_id',
                        'selectedValue' => $user->role_id
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
                        'selectedValue' => $user->gender
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
                        'selectedValue' => $user->status
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="address" class="required form-label">Address</label>
                    <textarea name="address" class="form-control form-control-solid required" required >{{$user->address}}</textarea>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="country" class="required form-label">Country</label>
                    <input type="text" value="{{ old('country') }}" value="Egypt" placeholder="Egypt" name="country" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="city" class="required form-label">City</label>
                    <input type="text" value="{{ old('city') }}" name="city" class="form-control form-control-solid"/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="phone" class="required form-label">Phone</label>
                    <input type="text" value="{{$user->phone}}" name="phone" class="form-control form-control-solid required" required/>
                </div>     
                        
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('users.index') }}" class="btn btn-dark">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
