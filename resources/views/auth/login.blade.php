@extends('layout.admin.auth-layout')

@section('title', 'Sign In')

@section('form')
    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="{{ route('user.home' , ['siteSetting' => $gymContext['slug'] ?? '']) }}" method="POST" action="{{ route('auth.login', ['siteSetting' => $gymContext['slug']]) }}">
        @csrf
        
        <input type="hidden" name="site_setting_id" value="{{ $gymContext['id'] ?? '' }}">
        
        @if(isset($gymContext))
            <div class="mb-3">
                 <h2 class="text-center">{{ $gymContext['name'] }}</h2>
            </div>
        @endif
        
        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
        </div>
        <div class="fv-row mb-8">
            <input type="text" placeholder="Email" name="email" class="form-control bg-transparent" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="fv-row mb-3" data-kt-password-meter="true">
            <div class="position-relative mb-3">
                <input class="form-control bg-transparent"
                    type="password" placeholder="Password" name="password" autocomplete="off" required />

                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                    data-kt-password-meter-control="visibility">
                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="d-flex flex-column flex-wrap gap-3 fs-base fw-semibold mb-8">
            <a href="{{ route('auth.forget-password.index', ['siteSetting' => $gymContext['slug']]) }}" class="link-primary">Forgot Password ?</a>
            <a href="{{ route('auth.register.index', ['siteSetting' => $gymContext['slug']]) }}" class="link-primary">Register</a>
        </div>

        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <span class="indicator-label">Sign In</span>

                <span class="indicator-progress">Please wait... 
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>

            </button>
        </div>
    </form>
@endsection

@section('js')
    @include('auth.assets.script', [
        'formId' => 'kt_sign_in_form',
        'submitButtonId' => 'kt_sign_in_submit',
        'successMessage' => 'Login successful!'
    ])
@endsection