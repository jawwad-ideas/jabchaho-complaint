@extends('frontend.layouts.auth-master')
@section('title', 'Reset Password')
@section('content')

<div class="container-auth d-flex">
    <div class="container">
        <h2>Reset Password</h2>
        @include('frontend.includes.partials.messages')
        <form name="reset" onkeypress="return event.keyCode != 13;" method="post" action="{{ route('reset.password.perform') }}"  class="form-horizontal col-md-12 mt-10p"  >
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="col-sm-12">
                    <input  type="password" name="password" class="form-control" placeholder="New Password" maxlength="20">
                    @if($errors->has('password'))
                        <div class="text-danger">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input  type="password" name="confirm_password" class="form-control"  placeholder="Confirm password" maxlength="20" >
                    @if($errors->has('confirm_password'))
                        <div class="text-danger">{{ $errors->first('confirm_password') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group">        
                <div class="col-sm-offset-2 col-sm-4">
                <button type="submit" class="btn btn-sm btn-block btn-secondary ">Reset Password</button>  
                </div>
            </div>
            


        </form>
    </div>
</div>

@endsection