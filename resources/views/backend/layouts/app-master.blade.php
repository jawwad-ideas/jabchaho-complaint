<!doctype html>
<html lang="en">

<head>
    <link type="image/x-icon" href="{{asset('assets/images/icons/fav.png')}}" rel="icon">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <title>{{config('app.name') }}</title> -->
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

    @include('backend.layouts.includes.navbar')
    <div class="loader"></div>
    <main class="container mt-1" style="background-color:#f3f4f3;">


        <div class="admin-main-container ">

            <div class="admin-sidebar sidebar bg-theme-dark p-0">
                <div class="sidebar-close-btn text-end">
                    <i class="fa fa-close fa-2x text-light"></i>
                </div>
                <div class="sidebar-logo-section px-4 pt-3 pb-1">
                    <div class="sidebar-logo">
                        <a href="/" class="text-decoration-none">
                            <img class="img-fluid " src="{{asset('assets/images/jc-logo.png')}}" />
                        </a>
                    </div>
                </div>
                <div class="admin-side-menu-section ">
                    <ul class="list-group list-group-flush mt-4 list-unstyled">
                        @if(Auth::user()->can('home.index') || Auth::user()->can('jabchaho-dashboard.index'))


                        <li class="list-item px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                data-bs-toggle="collapse" data-bs-target="#dashboard">
                                <span class="d-flex align-items-center gap-3 text-light">
                                    <i class="fa fa-solid fa-boxes-packing fa-2x text-theme-yellow-light"></i>
                                    Dashboard </span>
                                <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                            </div>

                            <div class="collapse mt-3 ms-5" id="dashboard" style="">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    @if(Auth::user()->can('home.index'))
                                    <li class="py-2 "><a href="{{ route('home.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-folder-tree text-theme-yellow-light"></i>
                                            Complaints</a>
                                    </li>
                                    @endif


                                    @if(Auth::user()->can('jabchaho-dashboard.index'))
                                    <li class="py-2 "><a href="{{ route('jabchaho-dashboard.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-folder-tree text-theme-yellow-light"></i>
                                            Jabchaho</a>
                                    </li>
                                    @endif



                                </ul>
                            </div>

                        </li>




                        @endif

                        @auth
                        @if(Auth::user()->can('users.index') || Auth::user()->can('roles.index') ||
                        Auth::user()->can('permissions.index') )
                        <li class="list-item px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                data-bs-toggle="collapse" data-bs-target="#users">
                                <span class="d-flex align-items-center gap-3 text-light">
                                    <i class="fa fa-users fa-2x text-theme-yellow-light"></i>
                                    Users </span>
                                <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                            </div>
                            <div class="collapse mt-3 ms-5" id="users" style="">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">

                                    @if(Auth::user()->can('users.index'))
                                    <li class="py-2 "><a href="{{ route('users.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-users text-theme-yellow-light"></i>
                                            Users</a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('roles.index'))
                                    <li class="py-2 "><a href="{{ route('roles.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-users-gear text-theme-yellow-light"></i>
                                            Roles </a>
                                    </li>
                                    @endif
                                    @if(Auth::user()->can('permissions.index'))
                                    <li class="py-2 "><a href="{{ route('permissions.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-person-chalkboard text-theme-yellow-light"></i>
                                            Permission </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        @endauth

                        @if(Auth::user()->can('complaints.index'))
                        <li class="list-item px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                data-bs-toggle="collapse" data-bs-target="#complaints">
                                <span class="d-flex align-items-center gap-3 text-light">
                                    <i class="fa fa-solid fa-boxes-packing fa-2x text-theme-yellow-light"></i>
                                    Complaints </span>
                                <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                            </div>

                            <div class="collapse mt-3 ms-5" id="complaints" style="">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    @if(Auth::user()->can('complaints.index'))
                                    <li class="py-2 "><a href="{{ route('complaints.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-folder-tree text-theme-yellow-light"></i>
                                            Complaints</a>
                                    </li>
                                    @endif


                                    @if(Auth::user()->can('reviews'))
                                    <li class="py-2 "><a href="{{ route('reviews') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-folder-tree text-theme-yellow-light"></i>
                                            Reviews</a>
                                    </li>
                                    @endif



                                </ul>
                            </div>

                        </li>
                        @endif

                            {{-- Order Profiling --}}
                            @if(Auth::user()->can('orders.index'))
                                <li class="list-item px-3 py-3">
                                    <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                         data-bs-toggle="collapse" data-bs-target="#orders">
                            <span class="d-flex align-items-center gap-3 text-light">
                                <i class="fa fa-solid fa-clipboard-list fa-2x text-theme-yellow-light"></i>
                                Orders </span>
                                        <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                                    </div>

                                    <div class="collapse mt-3 ms-5" id="orders" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            @if(Auth::user()->can('orders.index'))
                                                <li class="py-2 "><a href="{{ route('orders.index') }}/1"
                                                                     class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Pending Orders</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="collapse mt-3 ms-5" id="orders" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            @if(Auth::user()->can('orders.index'))
                                                <li class="py-2 "><a href="{{ route('orders.index') }}/2"
                                                                     class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Complete Orders</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    @if(Auth::user()->can('hold.order.list'))
                                        <div class="collapse mt-3 ms-5" id="orders" style="">
                                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                                <li class="py-2 "><a href="{{route('hold.order.list', ['type' => 'before_whatsapp']) }}"
                                                                    class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Before Wash whatsApp Hold </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                     @if(Auth::user()->can('hold.order.list'))
                                    <div class="collapse mt-3 ms-5" id="orders" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            <li class="py-2 "><a href="{{ route('hold.order.list', ['type' => 'after_whatsapp']) }}"
                                                                    class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                    <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        After Wash whatsApp Hold </a>
                                            </li>
                                        </ul>
                                    </div>
                                     @endif


                                    <div class="collapse mt-3 ms-5" id="orders" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            @if(Auth::user()->can('orders.barcode.images'))
                                                <li class="py-2 "><a href="{{ route('orders.barcode.images') }}"
                                                                     class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Barcodes</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="collapse mt-3 ms-5" id="orders" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        @if(Auth::user()->can('barcode.image.upload'))
                                                <li class="py-2 "><a href="{{ route('barcode.image.upload') }}"
                                                                     class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Barcode Image Upload</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>


                                    <div class="collapse mt-3 ms-5" id="orders" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">

                                        </ul>
                                    </div>

                                </li>
                            @endif
                            {{-- Order Profiling --}}


                            {{-- Machine Profiling --}}
                            @if(Auth::user()->can('machine.list') || Auth::user()->can('machine.details'))
                                <li class="list-item px-3 py-3">
                                    <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                         data-bs-toggle="collapse" data-bs-target="#machines">
                            <span class="d-flex align-items-center gap-3 text-light">
                                <i class="fa fa-solid fa-clipboard-list fa-2x text-theme-yellow-light"></i>
                                Washing </span>
                                        <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                                    </div>

                                    <div class="collapse mt-3 ms-5" id="machines" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            @if(Auth::user()->can('machine.list'))
                                                <li class="py-2 "><a href="{{ route('machine.list') }}"
                                                                     class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Machines</a>
                                                </li>
                                            @endif

                                            @if(Auth::user()->can('machine.details'))
                                            <li class="py-2 "><a href="{{ route('machine.details') }}"
                                                    class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                    <i class="fa fa-solid fa-table-columns text-theme-yellow-light"></i>
                                                    Details</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="collapse mt-3 ms-5" id="machines" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">

                                        </ul>
                                    </div>

                                </li>
                            @endif
                            {{-- Machine Profiling --}}


                            @if(Auth::user()->can('sunny.dryer'))
                                <li class="list-item px-3 py-3">
                                    <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer " data-bs-toggle="collapse" data-bs-target="#dryer">
                                        <span class="d-flex align-items-center gap-3 text-light">
                                            <i class="fa fa-solid fa-clipboard-list fa-2x text-theme-yellow-light"></i>
                                            Dryer
                                        </span>
                                        <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                                    </div>

                                    <div class="collapse mt-3 ms-5" id="dryer" style="">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                        
                                                <li class="py-2 "><a href="{{ route('sunny.dryer') }}/1"
                                                                        class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Pending</a>
                                                </li>

                                                <li class="py-2 "><a href="{{ route('sunny.dryer') }}/2"
                                                                        class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                        <i class="fa fa-solid fa-list text-theme-yellow-light"></i>
                                                        Completed</a>
                                                </li>
                                            

                                            
                                        </ul>
                                    </div>

                                </li>
                            @endif
                        @if(Auth::user()->can('configurations.form') || Auth::user()->can('complaints.status.index') )
                        <li class="list-item px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                data-bs-toggle="collapse" data-bs-target="#settings">
                                <span class="d-flex align-items-center gap-3 text-light">
                                    <i class="fa fa-cogs fa-boxes-packing fa-2x text-theme-yellow-light"></i>
                                    Settings </span>
                                <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                            </div>

                            <div class="collapse mt-3 ms-5" id="settings" style="">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    @if(Auth::user()->can('categories.index'))
                                    <!--<li class="py-2 "><a href="{{ route('categories.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-table-columns text-theme-yellow-light"></i>
                                            Categories</a>
                                    </li>-->
                                    @endif

                                    @if(Auth::user()->can('configurations.form'))
                                    <li class="py-2 "><a href="{{ route('configurations.form') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-table-columns text-theme-yellow-light"></i>
                                            Configurations</a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->can('complaints.status.index'))
                                    <li class="py-2 "><a href="{{ route('complaints.status.index') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-table-columns text-theme-yellow-light"></i>
                                            Complaint Status</a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->can('pricing'))
                                    <li class="py-2 "><a href="{{ route('pricing') }}"
                                            class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                            <i class="fa fa-solid fa-table-columns text-theme-yellow-light"></i>
                                            Pricing</a>
                                    </li>
                                    @endif


                                </ul>
                            </div>

                        </li>
                        @endif

                        @if(Auth::user()->can('report.by.user'))
                        <li class="list-item px-3 py-3">
                            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                                data-bs-toggle="collapse" data-bs-target="#reports">
                                <span class="d-flex align-items-center gap-3 text-light">
                                    <i class="fa fa-solid fa-file-export fa-2x text-theme-yellow-light"></i>
                                    Reports </span>
                                <i class="fa fa-solid fa-angle-down text-theme-yellow-light"></i>
                            </div>

                            <div class="collapse mt-3 ms-5" id="reports" style="">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                    @if(Auth::user()->can('report.by.user'))
                                        <li class="py-2 "><a href="{{ route('report.by.user') }}"
                                                class=" text-start text-decoration-none d-flex gap-3 align-items-center text-dark">
                                                <i class="fa fa-solid fa-table-columns text-theme-yellow-light"></i>
                                                Reports by User</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif

                    </ul>
                </div>
            </div>
            <div class="main-content">
            
                <div class="secondary-menu my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="side-bar-collapse-btn">
                            <i class="fa fa-bars text-muted mx-3 cursor-pointer" aria-hidden="true"></i>
                        </div>
                        <div class="header_right d-flex justify-content-between align-items-center">
                            <div class="profile_info">
                                @if(!empty(Auth::guard('web')->user()->profile_image))
                                <img src="{{asset(config('constants.files.profile'))}}/{{Auth::guard('web')->user()->profile_image}}"
                                    class="">
                                @else
                                <img src="{{asset('assets/images/default/profile.jpg')}}" class="">
                                @endif

                                <div class="profile_info_iner">
                                    <div class="profile_author_name">
                                        <!-- <p> Admin
                                        </p> -->
                                        <h6> {{Auth::guard('web')->user()->name}}</h6>
                                    </div>
                                    <div class="profile_info_details">
                                        @if(Auth::user()->can('profile.index'))
                                        <a href="{{ route('profile.index') }}" class="text-decoration-none my-2 p-0">My
                                            Profile </a>
                                        @endif
                                        <!-- <a href="#" class="text-decoration-none my-2 p-0">Settings</a> -->
                                        <a href="{{ route('logout.perform') }}"
                                            class="text-decoration-none my-2 p-0">Log
                                            out </a>
                                    </div>
                                </div>
                            </div>
                            <span class="ms-3 username">{{Auth::guard('web')->user()->name}}</span>

                        </div>
                    </div>
                </div>
                
                <div class="inner-content">
                    <div class="mt-2">
                        @include('backend.layouts.partials.messages')
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>

    </main>


    @section("scripts")
    <script>
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

    @show

    <script src="{!! url('assets/bootstrap/js/bootstrap.min.js') !!}"></script>
    <script src="{!! url('assets/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
    <script src="{!! url('assets/js/custom.js') !!}"></script>
</body>

</html>
