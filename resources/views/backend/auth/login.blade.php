@extends('backend.layouts.auth-master')
@section('title', 'Admin Login')
@section('content')
<div class="login-section bg-dark h-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-4 p-0 login-inner-section-col flex-grow-1 position-relative">
                <div class="login-inner-section">
                    <div class="logo-section my-5 p-4">
                        <a class="text-decoration-none" href="/complaint/admin"><img
                                src="{{asset('assets/images/jc-logo.png')}}" class="w-50" alt="logo" srcset=""></a>
                    </div>
                    <div class="login-form-heading">
                        <h1 class="text-light"><span class=" fw-bold"> Admin</span>
                        </h1>
                    </div>
                    <div class="login-form-heading text-light">

                        <h6>Please Login to our Complaint Portal.</h6>
                    </div>
                    
                    <div class="login-form-section p-4  my-5">
                        <div class="login-form">
                            @include('backend.layouts.partials.messages')
                            <form method="post" action="{{ route('login.perform') }}" class="login-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group mt-4">
                                    <input type="text" class="form-control border-0 p-3" name="username"
                                        value="{{ old('username') }}" placeholder="Username" required="required"
                                        autofocus>
                                    @if ($errors->has('username'))
                                    <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mt-4 d-flex">
                                    <input type="password" class="form-control border-0 p-3" name="password"
                                        value="{{ old('password') }}" placeholder="Password" required="required">
                                    @if ($errors->has('password'))
                                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="form-group  mt-4">
                                    <div class="col-sm-12">
                                            <span class="recaptcha-wrapper">{!! app('captcha')->display() !!}</span>
                                           
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <button type="submit"
                                                class="form-control btn text-dark bg-theme-yellow rounded submit p-2">Login</button>
                                        </div>
                                        <div class="col-lg-6  text-end">
                                            <div class="form-check">
                                                <label
                                                    class="form-check-label d-flex align-items-center justify-content-end gap-2"
                                                    for="flexCheckChecked">
                                                    <input class="form-check-input" type="checkbox" name="remember"
                                                        value="1" checked id="flexCheckChecked">
                                                    <small>
                                                        Remember Me
                                                    </small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="social-btns-section p-4 text-center d-none">
                            <div class="d-lg-flex text-center align-items-center justify-content-center gap-4">
                                <div class="social-btns d-flex justify-content-center gap-2 my-3">
                                    <i
                                        class="fa fa-brands fa-facebook-f rounded-circle p-2 border-light text-dark bg-theme-yellow"></i>

                                    <i
                                        class="fa fa-brands fa-twitter rounded-circle p-2 border-light text-dark bg-theme-yellow"></i>

                                    <i
                                        class="fa fa-solid fa-globe rounded-circle p-2 border-light text-dark bg-theme-yellow"></i>

                                    <i
                                        class="fa fa-brands fa-youtube rounded-circle p-2 border-light text-dark bg-theme-yellow"></i>

                                    <i
                                        class="fa fa-brands fa-instagram rounded-circle p-2 border-light text-dark bg-theme-yellow"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login-form-footer bg-theme-yellow p-1">
                        <div class="copyright">
                            <small>
                                <span>© </span>
                                <small>Copyright {{date('Y')}}</small>
                                <strong class="mx-1">Jabchaho. EZ Life Technologies.</strong>
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