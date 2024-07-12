@extends('frontend.layouts.auth-master')
@section('title', 'Forgot Password')
@section('content')
<div class="login-section bg-dark h-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-4 p-0 login-inner-section-col flex-grow-1 position-relative">

                <div class="login-inner-section">
                    <div class="logo-section my-5 p-4">
                        <a class="text-decoration-none" href="/home"><img
                                src="{{asset('assets/images/logo-two-white.png')}}" class="w-50" alt="logo"
                                srcset=""></a>
                    </div>
                    <div class="login-form-heading">
                        <h5>Welcome, Dear Citizen</h5>
                        <h6>Please Enter Your Email Address.</h6>
                    </div>
                    <div class="back-to-home">
                        <a href="/home"
                            class="text-end text-white d-flex justify-content-center text-white text-decoration-none gap-2 p-3">
                            <i class="fa fa-solid fa-arrow-left-long"></i> <small> Back to Home</small>
                        </a>
                    </div>
                    <div class="login-form-section p-4 bg-theme-dark my-5 ">
                        <div class="login-form">
                            <h5 class="text-white text-start">Forgot Password</h5>
                            @include('frontend.includes.partials.messages')
                            <form name="reset" onkeypress="return event.keyCode != 13;" method="post" action="{{ route('forgot.password.perform') }}" class="login-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group mt-4">
                                    <input type="text" autocomplete="off" class="form-control" id="email"  name="email" maxlength="50" placeholder="Email Address" value="{{old('email')}}">
                                    @if($errors->has('email'))
                                        <div class="text-danger mt-2 text-start">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mt-2">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6">
                                            <button type="submit"
                                                class="form-control btn text-white bg-theme-green rounded submit p-2">Reset Password</button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-12 mt-5">
                            <div class="d-lg-flex gap-2 justify-content-center">
                                <div class="forgot-pw d-none">
                                    <a class="text-white text-decoration-none"
                                        href="{{route('forgot.password.show')}}">Forgot
                                        Password</a>
                                </div>
                                <span class="d-none">|</span>

                                <div class="acc-signin">
                                    <span>Already have an account?</span>
                                    <span class="">
                                        <a class="text-theme-green text-decoration-none"
                                        href="{{route('signin.show')}}">Sign In</a>
                                    </span>
                                </div>

                            </div>
                        </div>
                        <div class="social-btns-section p-4 text-center d-none">
                            <div class="d-lg-flex text-center align-items-center justify-content-center gap-4 ">

                                <div class="social-btns d-flex justify-content-center gap-2 my-3">
                                    <i
                                        class="fa fa-brands fa-facebook-f rounded-circle p-2 border-light text-white bg-theme-green"></i>

                                    <i
                                        class="fa fa-brands fa-twitter rounded-circle p-2 border-light text-white bg-theme-green"></i>

                                    <i
                                        class="fa fa-solid fa-globe rounded-circle p-2 border-light text-white bg-theme-green"></i>

                                    <i
                                        class="fa fa-brands fa-youtube rounded-circle p-2 border-light text-white bg-theme-green"></i>

                                    <i
                                        class="fa fa-brands fa-instagram rounded-circle p-2 border-light text-white bg-theme-green"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login-form-footer bg-theme-green p-1">
                        <div class="copyright">
                            <small>
                                <span>© </span>
                                <small>Copyright</small>
                                <strong class="mx-1">MQM Connect</strong>
                                <small>All Rights Reserved</small>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 p-0 flex-grow-1 d-lg-block d-none auth-img-container-bg">

                <div class="bg-image-gradient">
                    <!-- <img src="{{asset('assets/images/karachi-aerial-one.jpg')}}" class="img-responsive" alt=""> -->
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
