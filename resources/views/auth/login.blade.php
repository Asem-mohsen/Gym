@extends('auth.layouts.master')

@section('title' , 'Sign In')

@section('content')
    <section class="auth h-100">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <div class="h-100">
            <div class="row noHegiht h-100">
                <div class="wrapper h-100">
                    <div class="col-md-6 box details" style="background-image:url({{ asset("assets/dist/img/web/SignIn/RobotHand.webp")}});">
                    </div>
                    <div class="col-md-5 box">
                        <div class="form">
                            <div class="content">
                                <a href="{{route('admin.dashboard')}}" class="ZhomeName">Gym Name</a>
                                <h3 class="login-form__title">login to your account</h3>
                            </div>

                            <form method='POST' action="{{ route('auth.login') }}">
                                @csrf
                                <div class="inputs login-form__form">
                                    <div class="login-form__field position-relative" style="height:82px">
                                        <input type="email" id="email" name="email" placeholder="Email Address" required />
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />

                                    <div class="login-form__field position-relative password-input" style="height:82px">
                                        <input type="password" class="password" id="password" name="password" placeholder="Passowrd" required/>
                                        <i class="fa fa-eye toggler" id="eye"></i>
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-1" />

                                    <div class="flex items-center justify-end mt-4">
                                        @if (Route::has('password.request'))
                                            <a class="ForgetPassword underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                                Foreget your password ? 
                                            </a>
                                        @endif

                                        <button type="submit" class="thm-btn contact-one__btn">
                                            Login
                                        </button>
                                    </div>


                                    <p class="text-center mt-2 mb-3" style="color: #acacac;">Login with social media accounts</p>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-1 small-sidenav">
                        <ul class="small-sidenav-ul">
                            <li><a href="{{ config('app.frontend_url') }}" title="Website"><i class="fa-solid fa-house"></i></a></li>
                            <li><a href="{{ config('app.frontend_url') . '/shop'}}" title="Shop"><i class="fa-solid fa-store"></i></a></li>
                            <li><a href="{{ config('app.frontend_url') . '/about'}}" title="Smarven"><i class="fa-solid fa-circle-info"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('Js')
    <script>
        $("[placeholder]").focus(function () {
            $(this).attr("data-text", $(this).attr("placeholder"));
            $(this).attr("placeholder", "");
        }).blur(function () {
            $(this).attr("placeholder", $(this).attr("data-text"));
        });

        // Eye toggler Password
        document.getElementById('eye').addEventListener('click', function () {
            let passwordField = document.getElementById('password');
            let eyeIcon = document.getElementById('eye');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }

            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
@stop

