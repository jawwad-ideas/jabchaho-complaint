@extends('backend.layouts.auth-master')
@section('title', 'Admin Login')
@section('content')
<div class="login-section bg-dark h-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-4 p-0 login-inner-section-col flex-grow-1 position-relative">
                <div class="login-inner-section">
                    <div class="logo-section my-3 p-4">
                        <a class="text-decoration-none" href="/complaint/admin"><img
                                src="{{asset('assets/images/jc-logo.png')}}" class="w-50" alt="logo" srcset=""></a>
                    </div>
                    <div class="login-form-heading">
                        <h1 class="text-light"><span class=" fw-bold"> Setup Google 2FA</span>
                        </h1>
                    </div>
                    <div class="login-form-heading text-light">
                        <p>Scan this QR code with your Google Authenticator app:</p>
                        <img src="{{ $qrCodePath }}" alt="QR Code" width="250">
                    </div>

                   
                 
                    <div class="login-form-section p-2  my-3">
                        <div class="login-form">
                            @include('backend.layouts.partials.messages')
                            <form method="POST" action="{{ route('google2fa.enable') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text"  class="form-control" name="google2fa_token" placeholder="Enter Google 2FA Token" required>
                                </div>

                                <div class="col-lg-12 my-2">
                                    <button type="submit" class="form-control btn text-dark bg-theme-yellow rounded submit p-2">Submit</button>
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