<!DOCTYPE html>
<html>

<head>
    <link type="image/x-icon" href="{{asset('assets/images/icons/fav.png')}}" rel="icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <title>@yield('title')</title> -->
    <title>Complaint Portal</title>
    
    <script src="{!! url('assets/js/jquery.min.js') !!}"></script>
    <!-- Bootstrap core CSS -->
    <link href="{!! url('assets/bootstrap/css/font-awesome.min.css') !!}" rel="stylesheet">
    <link href="{!! url('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet" media='all'>
    <link rel="stylesheet" href="{!! url('assets/bootstrap/css/bootstrap-print.min.css') !!}" media="print">
    <link href="{!! url('assets/css/admin-custom.css') !!}?v={{config('constants.css_version')}}" rel="stylesheet">
    <link href="{!! url('assets/css/extended.css') !!}?v={{config('constants.css_version')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{!! url('assets/css/adminsite.css') !!}?v={{config('constants.css_version')}}" rel="stylesheet">
    <!-- toastr css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css')}}">
    <script type="text/javascript" src="{{ asset('assets/js/toastr.min.js')}}"></script>
</head>

<body>

    <div class="loader"></div>

    <div id="container" class="ff-gothambook">
        <div class="admin-main-container ">

            <div class="admin-sidebar sidebar bg-theme-dark p-0">
                @include('frontend.layouts.sidebar')
            </div>
            <div class="main-content">
                <div class="mt-2">
                    @include('frontend.includes.partials.messages')
                </div>
                <div class="secondary-menu my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="side-bar-collapse-btn">
                            <i class="fa fa-bars text-muted mx-3 cursor-pointer" aria-hidden="true"></i>
                        </div>

                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="profile_info">
                                <img src="{{asset('assets/images/default/profile.jpg')}}" class="">

                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <!-- <p> Complainant
                                        </p> -->
                                        <h6> {{Auth::guard('complainant')->user()->full_name}}</h6>
                                    </div>
                                    <div class="profile_info_details">
                                        <!-- <a href="#" class="text-decoration-none my-2 p-0">My Profile </a> -->
                                        <a data-toggle="modal" data-target="#myModal" id="open" href="#"
                                            class="text-decoration-none my-2 p-0">Change Password</a>
                                        <a href="{{route('signout.perform')}}" class="text-decoration-none my-2 p-0">Log
                                            out </a>
                                    </div>
                                </div>
                            </div>
                            <span class="ms-3 username">{{Auth::guard('complainant')->user()->full_name}}</span>

                        </div>

                    </div>
                </div>
                <div class="inner-content">
                    @yield('content')
                </div>
            </div>
        </div>
        <div class="mt-5 footerDiv">
            @include('frontend.layouts.footer')
        </div>
        @include('frontend.includes.modals.change-password')
    </div>


    <script src="{!! url('assets/js/custom.js') !!}"></script>
    <script src="{!! url('assets/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
    <script src="{!! url('assets/bootstrap/js/bootstrap.min.js') !!}"></script>


    <script>
    // adding class on sidebar mobile version
    // $(document).on('click', '.navbar-toggler', function() {
    //     $(this).parent().toggleClass("sidebar-active")
    // });
    // $(document).on('click', '.side-bar-close-icon', function() {
    //     $('.navbar').removeClass('sidebar-active');
    //     $('.side-bar').removeClass('in');
    // });


    $('.side-bar-collapse-btn i ').click(function() {
        $('.admin-sidebar').toggleClass('hidden');
        $('.admin-main-container .main-content').toggleClass('full-width');
    });
    $(".sidebar-close-btn").click(function() {
        $('.admin-sidebar').addClass('hidden');
    })
    $(document).ready(function() {
        if (window.innerWidth < 767) {
            $('.admin-sidebar').addClass('hidden');
        }

    });
    </script>



</body>

</html>