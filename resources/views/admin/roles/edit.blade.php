@extends('layout.master')

@section('title', 'Role')

@section('content')

    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $role->name }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            @if ($role->users_count > 0)
                                {{ 'Has ' . $role->users_count . $role->name }}
                            @else
                                {{ 'No ' . $role->name .' for this Role' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <form action="{{ route('roles.update', $role->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-2">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Settings</p>
                                <a href="" class="btn btn-primary btn-sm ms-auto m-2">Settings</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="example-text-input" class="form-control-label">Role Name</label>
                                    <input class="form-control" type="text" name="name" required value="{{ $role->name }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">description</label>
                                        <textarea class="form-control" name="description">{{ $role->description }}</textarea>
                                    </div>
                                    @error('description')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <p class="text-uppercase text-sm mt-5 "> {{ $role->name }} Department Users</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul style="list-style: none;display: flex;justify-content: center;gap: 20px;flex-wrap: wrap;">
                                        @if ($role->users_count > 0)
                                            @foreach ($role->users as $user)
                                                <a href="{{ route('admins.show', $user->id) }}" target="_blank" data-original-title="Visit Profile" style="display: block;width: fit-content;">
                                                    <li class="p-3 mb-4" style="width: max-content;border: 1px solid #eee;border-radius: 18px;min-width: 239px;">
                                                        <div class="d-flex px-2 py-1">
                                                            <div>
                                                                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="avatar avatar-sm me-3" alt="user1">
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </a>
                                            @endforeach
                                        @else
                                            {{ 'No users for this Role' }}
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <hr class="horizontal dark">

                            <p class="text-uppercase text-sm">Control</p>
                            <div class="justify-content-center row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-md btn-success w-100 mt-4 mb-0">Update</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a href="{{ route('roles.index') }}" class="btn btn-md btn-danger w-100 mt-4 mb-0">Cancel</a>
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
