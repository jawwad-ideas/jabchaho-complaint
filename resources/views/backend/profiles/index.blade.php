@extends('backend.layouts.app-master')
<style>
.avatar-wrapper {
    position: relative;
    height: 150px;
    width: 150px;
    margin: 40px 0;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 1px 1px 15px -5px black;
    transition: transform .3s ease;
}

.avatar-wrapper:hover {
    transform: scale(1.05);
    cursor: pointer;
}

.profile-pic {
    height: 100%;
    width: 100%;
    transition: opacity .3s ease;
}

.profile-pic:after {
    content: "\f007";
    font-family: FontAwesome;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #ecf0f1;
    color: #fce100;
    text-align: center;
    font-size: 70px;
}

/* Centering the icon */

.upload-button {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity .3s ease;
}

.upload-button .fa-arrow-up {
    font-size: 80px;
    colo font-size: 70px;
    color: #fce100;
}

.avatar-wrapper:hover .profile-pic {
    opacity: .5;
}

.avatar-wrapper:hover .upload-button {
    opacity: .9;
}
</style>
@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">My Profile</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="{{route('profile.update')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Update Your Information.</h4>
                    </div>
                </div>


                <div class="container mt-4">

                    <div class="mb-3">
                        <div class="avatar-wrapper">
                            @if(!empty(Auth::guard('web')->user()->profile_image))
                                <img src="{{asset(config('constants.files.profile'))}}/{{Auth::guard('web')->user()->profile_image}}"
                                    class="profile-pic">
                            @else
                                <img src="" class="profile-pic">
                            @endif
                            <div class="upload-button">
                                <i class="fa fa-arrow-up" aria-hidden="true"></i>
                            </div>
                            <input class="file-upload" type="file" accept="image/*" id="profile_image" name="profile_image" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input value="{{$userData['name'] ? $userData['name'] : ''}}" type="text" class="form-control" name="name"
                            placeholder="Full Name">

                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">User Name</label>
                        <input value="{{$userData['username'] ? $userData['username'] : ''}}" type="text" class="form-control" name="username"
                            placeholder="User Name">

                    </div>


                    <input value="{{$userData['id']}}" type="text" class="form-control" name="user_id" hidden>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input value="{{$userData['email'] ? $userData['email'] : ''}}" type="email" class="form-control" name="email"
                            placeholder="Email address">

                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Mobile Number</label>
                        <input value="{{$userData['mobile_number'] ? $userData['mobile_number'] : ''}}" type="text" class="form-control"
                            name="mobile_number" placeholder="Mobile Number" maxlength="11" minlength="11"
                            onkeydown="return isNumberKey(event);">

                    </div>

                    <div>&nbsp;</div>
                    @if(Auth::user()->can('profile.update'))
                        <div class="mb-3">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Save</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
<script>
var readURL = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('.profile-pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".file-upload").on('change', function() {
    readURL(this);
});

$(".upload-button").on('click', function() {
    $(".file-upload").click();
});
</script>
@endsection