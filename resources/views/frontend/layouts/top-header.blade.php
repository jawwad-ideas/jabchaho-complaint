<header class="p-3 theme-green-bg tc-white-imp p-absolute w-100 header-blade ff-gothambook d-none ">
    <div class="container jc-left top-header-container">
        <a class="navbar-brand" href=""><img src="{{asset('assets/images/logo.png')}}" /></a>
        <ul class="custom-nav">
            <li class="a-s-center "><a href="#_"
                    class="nav-link f-18px px-2 tc-white-imp text-decoration-none">{{Auth::guard('complainant')->user()->full_name}}</a>
            </li>
            <li class="nav-item dropdownt">
                <a class="nav-link px-2 text-decoration-none " href="#" role="button" data-bs-toggle="dropdown">

                    @if(!empty(Auth::guard('complainant')->user()->profile_image))
                    <img src="{{asset(config('constants.files.profile'))}}/{{Auth::guard('complainant')->user()->profile_image}}"
                        class="dropdownImage">
                    @else
                    <img src="{{asset('assets/images/default/profile.jpg')}}" class="dropdownImage">
                    @endif

                </a>
                <div class="dropdown-menu profile-in">
                    <ul class="bg-dark">
                        <li><a href="" class="dropdown-item  tc-white-imp">Home</a></li>
                        <li><a class="dropdown-item  tc-white-imp" data-toggle="modal" data-target="#myModal"
                                id="open">Change Password</a></li>
                        <li><a href="{{route('signout.perform')}}" class="dropdown-item  tc-white-imp">Signout</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</header>