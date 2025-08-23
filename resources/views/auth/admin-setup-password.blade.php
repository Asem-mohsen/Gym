@extends('layout.admin.auth-layout')

@section('title','Set Up Password')

@section('form')
<div class="card">
    <div class="card-body">
        <h1 class="text-center mb-8">Welcome to {{ config('app.name') }}</h1>
        
        <p class="text-center mb-8">Please set up your password to complete your account setup.</p>
        
        <form method="POST" action="{{ route('auth.admin-setup-password') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div data-kt-password-meter="true">
                <label class="form-label fw-semibold fs-6 mb-2">
                    Password
                </label>
                <div class="position-relative mb-3">
                    <input class="form-control form-control-solid required" type="password" placeholder="" name="password" autocomplete="off" required />

                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                </div>
            </div>

            <div data-kt-password-meter="true">
                <label class="form-label fw-semibold fs-6 mb-2">
                    Confirm Password
                </label>
                <div class="position-relative mb-3">
                    <input class="form-control form-control-solid required" type="password" placeholder="" name="password_confirmation" autocomplete="off" required />
                </div>
            </div>

            <div class="d-flex flex-wrap justify-content-center pb-lg-0">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary me-4">
                    <span class="indicator-label">Set Password</span>
                    <span class="indicator-progress">Please wait... 
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
