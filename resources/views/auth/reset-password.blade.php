@extends('layout.admin.auth-layout')

@section('title','Reset Password')

@section('form')
<div class="card">
    <div class="card-body">
        @if(isset($gymContext))
            <div class="alert alert-info mb-8">
                <strong>Resetting password for:</strong> {{ $gymContext['name'] }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('auth.forget-password.reset', ['siteSetting' => $gymContext['slug'] ?? '']) }}">
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
                    <input class="form-control form-control-solid required" type="password" placeholder="" name="password_confirmation" id="password_confirmation" autocomplete="off" required />

                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    </span>
                </div>
            </div>

            <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <span class="indicator-label">Reset Password</span>

                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

