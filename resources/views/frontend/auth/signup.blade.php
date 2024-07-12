@extends('frontend.layouts.auth-master')
@section('title', 'Sign Up')
@section('content')
<div class="login-section bg-dark h-100">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-lg-4 p-0 login-inner-section-col flex-grow-1 position-relative ">

                <div class="login-inner-section">
                    <div class="logo-section my-5 p-4">
                        <a class="text-decoration-none" href="/home"><img
                                src="{{asset('assets/images/jc-logo.svg')}}" class="w-50" alt="logo"
                                srcset=""></a>
                    </div>
                    <div class="login-form-heading">
                        <h5>Welcome, Dear Citizen</h5>
                        <h6>Please Signup to our Complaint Portal.</h6>
                    </div>
                    <div class="back-to-home">
                        <a href="/home"
                            class="text-end text-white d-flex justify-content-center text-white text-decoration-none gap-2 p-3">
                            <i class="fa fa-solid fa-arrow-left-long"></i> <small> Back to Home</small>
                        </a>
                    </div>

                    <div class="login-form-section p-4 bg-theme-dark my-5">
                        <div class="login-form">
                            @include('frontend.includes.partials.messages')


                            <form class="form-horizontal" action="{{ route('signup.perform') }}" method="Post"
                                autocomplete="off" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group mt-4">
                                    <div class="col-sm-12">
                                        <input type="text" autocomplete="off" class="form-control" id="full-name"
                                            placeholder="Enter Full Name *" name="full_name" maxlength="50"
                                            value="{{old('full_name')}}" onpaste="return false;"
                                            onkeydown="return isAlphabatKey(this);">
                                        @if($errors->has('full_name'))
                                        <div class="text-danger">{{ $errors->first('full_name') }}</div>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group mt-4">
                                    <div class="col-sm-12 d-flex align-items-center ">
                                        @if(!empty($genderOptions))
                                        @foreach($genderOptions as $key=>$value)
                                        <input class="form-check-input" type="radio" name="gender" id="gender-{{$key}}"
                                            value="{{$key}}" @if((old('_token') && old('gender')==$key)) {{'checked'}}
                                            @if(old('_token')==null && $key==1) {{'checked'}} @endif @endif>
                                        <span class="form-check-label mx-2" for="gender-{{$key}}">{{$value}}</span>
                                        @endforeach
                                        @endif

                                    </div>
                                    @if($errors->has('gender'))
                                    <div class="text-danger">{{ $errors->first('gender') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mt-4">
                                    <div class="col-sm-12">
                                        <input type="text" autocomplete="off" class="form-control" id="cnic"
                                            placeholder="Enter CNIC (Without dashes) *" name="cnic" maxlength="15"
                                            value="{{old('cnic')}}" onpaste="return false;"
                                            onkeydown="return isNumberKey(event);" oninput="formatCNIC(this);">
                                        @if($errors->has('cnic'))
                                        <div class="text-danger">{{ $errors->first('cnic') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <div class="col-sm-12">
                                        <input type="text" autocomplete="off" class="form-control" id="mobile_number"
                                            placeholder="Enter Mobile Number*" name="mobile_number" maxlength="11"
                                            value="{{old('mobile_number')}}" onpaste="return false;"
                                            onkeydown="return isNumberKey(event);">
                                        @if($errors->has('mobile_number'))
                                        <div class="text-danger">{{ $errors->first('mobile_number') }}</div>
                                        @endif
                                    </div>
                                </div>



                                <div class="form-group mt-4">
                                    <div class="col-sm-12">
                                        <input type="text" autocomplete="off" class="form-control" id="email"
                                            placeholder="Enter email *" name="email" maxlength="50"
                                            value="{{old('email')}}">
                                        @if($errors->has('email'))
                                        <div class="text-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                </div>


                                <div class="form-group mt-4">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="password" autocomplete="off" class="form-control" id="password"
                                                placeholder="Enter Password *" name="password" maxlength="20">
                                            @if($errors->has('password'))
                                            <div class="text-danger">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" autocomplete="off" class="form-control"
                                                id="confirm_password" placeholder="Enter Confirm Password *"
                                                name="confirm_password" maxlength="20">
                                            @if($errors->has('confirm_password'))
                                            <div class="text-danger">{{ $errors->first('confirm_password') }}</div>
                                            @endif
                                        </div>


                                    </div>
                                    <!--row-->
                                </div>

                                <div class="form-group my-2">
                                    <div class="col-sm-12">
                                        <span class="recaptcha-wrapper">{!! Captcha::display() !!}</span>
                                        @if($errors->has('g-recaptcha-response'))
                                        <div class="text-danger">{{ $errors->first('g-recaptcha-response') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <div class="col-sm-12 t-a-center" style="margin-bottom: 5px;">
                                        <button type="submit"
                                            class="form-control btn text-white bg-theme-green rounded submit p-2">Signup</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="col-lg-12 mt-5">
                            <div class="d-lg-flex gap-2 justify-content-center">
                                <div class="acc-signup">
                                    <span>Already have an account?</span>
                                    <span class="">
                                        <a class="text-theme-green text-decoration-none"
                                            href="{{route('signin.show')}}">Signin </a>
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



<script>
const fileInput = document.querySelector('input[type="file"]');
var isAdvancedUpload = function() {
    var div = document.createElement('div');
    return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window &&
        'FileReader' in window;
}();
$form = $(".form-horizontal label.file-wrapper");
var droppedFiles = false;
if (isAdvancedUpload) {
    $form.addClass('has-advanced-upload');

    $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
        })
        .on('dragover dragenter', function() {
            $form.addClass('is-dragover');
        })
        .on('dragleave dragend drop', function() {
            $form.removeClass('is-dragover');
        })
        .on('drop', function(e) {
            droppedFiles = e.originalEvent.dataTransfer.files;
            $(".dropped_file_name").html("<h2><span class='fa fa-file'></span><span class='name'> " + droppedFiles[
                0].name + "</span></h2>");
            $(".box__dragndrop").addClass("disable");

            fileInput.files = droppedFiles;
        });
}
$("input[type=file]").change(function(e) {
    droppedFiles = e.originalEvent.target.files;
    $(".dropped_file_name").html("<h2><span class='fa fa-file'></span><span class='name'> " + droppedFiles[0]
        .name + "</span></h2>");
    $(".box__dragndrop").addClass("disable");
})
// A $( document ).ready() block.
$(document).ready(function() {
    ShowHideCodes();
});

function ShowHideCodes() {
    let country = $('#country').val();

    if (country == 'PK') {
        $('#div-int-mobile').hide();
        $('#div-local-mobile').show();
    } else {
        $('#div-local-mobile').hide();
        $('#div-int-mobile').show();
    }
}
</script>


@stop