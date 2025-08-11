@extends('layout.admin.master')

@section('title' , 'Create User')

@section('main-breadcrumb', 'User')
@section('main-breadcrumb-link', route('users.index'))

@section('sub-breadcrumb','Create User')

@section('content')

 <div class="col-md-12 mb-md-5 mb-xl-10">
    <form action="{{ route(name: 'users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="mb-10 col-md-6">
                    <label for="name" class="required form-label">Name</label>
                    <input type="text" value="{{ old('name') }}" name="name" class="form-control form-control-solid required" required/>
                </div>
                <div class="mb-10 col-md-6">
                    <label for="email" class="required form-label">Email address</label>
                    <input type="email" value="{{ old('email') }}" name="email" class="form-control form-control-solid required" required/>
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
                        'changeFuncion' => 'toggleTrainerSection()'
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
                    ])
                </div>
                <div class="mb-10 col-md-6">
                    <label for="status" class="required form-label">Status</label>
                    @php
                        $options = [
                            ['value' => 'active', 'label' => 'Active'],
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
                    <label for="image" class="form-label">User Image</label>
                    <input class="form-control" type="file" name="image" accept="image/*">
                </div>
                <div class="mb-10 col-md-6">
                    <label for="address" class="required form-label">Address</label>
                    <textarea name="address" class="form-control form-control-solid required" required >{{ old('address') }}</textarea>
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
                    <input type="text" value="{{ old('phone') }}" name="phone" class="form-control form-control-solid required" required/>
                </div>

                <!-- Trainer Information Section -->
                <div id="trainer-info-section" class="card col-12" style="display: none;">

                    <h4 class="mb-4 mt-5">Trainer Information</h4>
                    
                    <div class="row">
                        <div class="mb-10 col-md-6">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" max="999.99" value="{{ old('weight') }}" name="weight" class="form-control form-control-solid" placeholder="e.g., 75.5"/>
                        </div>
                        <div class="mb-10 col-md-6">
                            <label for="height" class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" min="0" max="999.99" value="{{ old('height') }}" name="height" class="form-control form-control-solid" placeholder="e.g., 175.0"/>
                        </div>
                        <div class="mb-10 col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" value="{{ old('date_of_birth') }}" name="date_of_birth" class="form-control form-control-solid"/>
                        </div>
                        <div class="mb-10 col-12">
                            <label for="brief_description" class="form-label">Brief Description / Highlights</label>
                            <textarea name="brief_description" class="form-control form-control-solid" rows="4" placeholder="Tell us about the trainer's experience, specialties, achievements...">{{ old('brief_description') }}</textarea>
                        </div>
                        <div class="mb-10 col-md-6">
                            <label for="facebook_url" class="form-label">Facebook URL</label>
                            <input type="url" value="{{ old('facebook_url') }}" name="facebook_url" class="form-control form-control-solid" placeholder="https://facebook.com/username"/>
                        </div>
                        <div class="mb-10 col-md-6">
                            <label for="twitter_url" class="form-label">Twitter URL</label>
                            <input type="url" value="{{ old('twitter_url') }}" name="twitter_url" class="form-control form-control-solid" placeholder="https://twitter.com/username"/>
                        </div>
                        <div class="mb-10 col-md-6">
                            <label for="instagram_url" class="form-label">Instagram URL</label>
                            <input type="url" value="{{ old('instagram_url') }}" name="instagram_url" class="form-control form-control-solid" placeholder="https://instagram.com/username"/>
                        </div>
                        <div class="mb-10 col-md-6">
                            <label for="youtube_url" class="form-label">YouTube URL</label>
                            <input type="url" value="{{ old('youtube_url') }}" name="youtube_url" class="form-control form-control-solid" placeholder="https://youtube.com/@username"/>
                        </div>
                    </div>
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

@section('js')
    @include('admin.users.scripts.scripts')
@endsection
