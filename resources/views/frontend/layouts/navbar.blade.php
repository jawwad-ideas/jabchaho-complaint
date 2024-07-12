<header class="p-3 text-white header-blade ff-gothambook">
    <div class="container jc-left">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark jc-left">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href=""><img src="{{asset('assets/images/logo.png')}}" /></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="nav-pos">
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <ul class="navbar-nav mr-auto">
                        @if(!Auth::guard('complainant')->user())
                        <li class="nav-item"><a href="{{ route('signin.show') }}" class="nav-link px-2 f-14px">LOGIN</a>
                        </li>
                        <span class="mobile-hide"> | </span>
                        <li class="nav-item"><a href="{{ route('signup.show') }}"
                                class="nav-link px-2 f-14px">REGISTER</a></li>
                        @else
                        <li class="nav-item"><a href="" class="nav-link px-2 f-14px">PROFILE</a></li>
                        @endif
                    </ul>

                </div>
            </div>
        </nav>
    </div>
</header>
<div class="p-absolute w-100 h-300px banner-cover"></div>
<script src="{{asset('assets/js/navbar-header.js')}}"></script>