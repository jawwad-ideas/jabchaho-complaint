@extends('backend.layouts.auth-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    </div>
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="login-wrap p-4 p-md-5">
                <div class="icon d-flex align-items-center justify-content-center">
                    <span class="fa fa-user-o"></span>
                </div>
                <h3 class="text-center mb-4">Register</h3>
                @include('backend.layouts.partials.messages')
                <form method="post" action="{{ route('register.perform')  }}" class="login-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group ">
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                            placeholder="Email address" required="required" autofocus>
                        @if ($errors->has('email'))
                        <span class="text-danger text-left">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="form-group ">
                        <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                            placeholder="Username" required="required" autofocus>
                        @if ($errors->has('username'))
                        <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" value="{{ old('password') }}"
                            placeholder="Password" required="required">
                        @if ($errors->has('password'))
                        <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group form-floating mb-3">
                        <input type="password" class="form-control" name="password_confirmation"
                            value="{{ old('password_confirmation') }}" placeholder="Confirm Password"
                            required="required">
                        @if ($errors->has('password_confirmation'))
                        <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>





@endsection