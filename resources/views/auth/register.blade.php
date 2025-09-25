@extends('layout.admin.auth-layout')

@section('title', 'Sign Up')

@section('form')
    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST" action="{{ route('auth.register', ['siteSetting' => $siteSetting->slug]) }}">
        @csrf
        
        @if(isset($siteSetting) && $siteSetting->getFirstMediaUrl('gym_logo') != null)
            <img src="{{ $siteSetting->getFirstMediaUrl('gym_logo') }}" alt="{{ $siteSetting->gym_name }}" class="d-flex justify-content-center m-auto h-60px h-lg-75px" />
        @elseif(isset($siteSetting))
            <div class="mb-3">
                <h2 class="text-center">{{ $siteSetting->gym_name }}</h2>
            </div>
        @endif
        
        <input type="hidden" name="site_setting_id" value="{{ $siteSetting->id ?? '' }}">
        
        <div class="text-center mb-11 mt-5">
            <h1 class="text-gray-900 fw-bolder mb-3">
                {{ $brandingData['branding']['page_texts']['register']['title'] ?? 'Create Account' }}
            </h1>
            @if(isset($brandingData['branding']['page_texts']['register']['subtitle']))
                <p class="text-gray-600 mb-0">{{ $brandingData['branding']['page_texts']['register']['subtitle'] }}</p>
            @endif
        </div>
        
        <div class="fv-row mb-8">
            <input type="text" placeholder="Full Name" name="name" class="form-control bg-transparent" value="{{ old('name') }}" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        
        <div class="fv-row mb-8">
            <input type="email" placeholder="Email" name="email" class="form-control bg-transparent" value="{{ old('email') }}" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        
        <div class="fv-row mb-8">
            <input type="text" placeholder="Phone (Optional)" name="phone" class="form-control bg-transparent" value="{{ old('phone') }}" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        
        <div class="fv-row mb-8" data-kt-password-meter="true">
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
        
        <div class="fv-row mb-8" data-kt-password-meter="true">
            <div class="position-relative mb-3">
                <input class="form-control bg-transparent"
                    type="password" placeholder="Confirm Password" name="password_confirmation" autocomplete="off" required />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                    data-kt-password-meter-control="visibility">
                        <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <a href="{{ route('auth.login.index', ['siteSetting' => $siteSetting->slug]) }}" class="link-primary">
                {{ $brandingData['branding']['page_texts']['register']['login_link_text'] ?? 'Already have an account? Sign In' }}
            </a>
        </div>

        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                <span class="indicator-label">{{ $brandingData['branding']['page_texts']['register']['button_text'] ?? 'Create Account' }}</span>
                <span class="indicator-progress">Please wait... 
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </form>
@endsection


@section('js')

    @include('auth.assets.script', [
        'formId' => 'kt_sign_up_form',
        'submitButtonId' => 'kt_sign_up_submit',
        'successMessage' => 'Account created successfully!'
    ])

@endsection
