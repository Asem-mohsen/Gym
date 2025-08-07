@extends('layout.admin.auth-layout')

@section('title','Forget Password')

@section('form')
    <div class="card">
        <div class="card-body">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('auth.forget-password.send-code') }}">
                    @csrf

                    <div class="fv-row mb-8">
                        <input type="text" placeholder="Email" name="email" value="{{ old('email') }}" class="form-control bg-transparent" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                            <span class="indicator-label">Email Password Reset Link</span>
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>


                <a href="{{ route('auth.login.index') }}" id="kt_sign_in_submit" class="btn btn-secondary mt-3">
                    <span class="indicator-label">{{ __('Back to Login') }}</span>
                    <span class="indicator-progress">Please wait... 
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </a>
 
            </div>
        </div>
    </div>
@endsection