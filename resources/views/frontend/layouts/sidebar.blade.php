<div class="sidebar-close-btn text-end">
    <i class="fa fa-close fa-2x text-white"></i>
</div>
<div class="sidebar-logo-section px-4 pt-3 pb-1">
    <div class="sidebar-logo">
        <a href="/home" class="text-decoration-none">
            <img class="img-fluid " src="{{asset('assets//website/images/logo-two-white.png')}}" />
        </a>
    </div>
</div>
<div class="admin-side-menu-section ">
    <ul class="list-group list-group-flush mt-4 list-unstyled ">
        <li class="list-item px-2 @if(Request::segment(1) =='complaints') active @endif py-3">
            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                data-bs-toggle="collapse" data-bs-target="#complaints">
                <span class="d-flex align-items-center gap-3 text-white"><i
                        class="fa fa-person-chalkboard text-theme-green fa-2x"></i>

                    Complaints </span>
                <i class="fa fa-solid fa-angle-down text-theme-green"></i>
            </div>
            <div class="collapse mt-3 ms-5" id="complaints" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li class="py-2"><a href="{{route('complaints')}}"
                            class="text-start text-decoration-none d-flex gap-3 align-items-center text-white">
                            <i class="fa fa-solid fa-list-check text-theme-green"></i> My
                            Complaints</a>
                    </li>
                    <li class="py-2"><a href="{{route('complaints.create')}}"
                            class="text-start text-decoration-none d-flex gap-3 align-items-center text-white">
                            <i class="fa fa-solid fa-upload text-theme-green"></i>
                            Add New </a></li>
                </ul>
            </div>


        </li>
    </ul>
</div>

<div class="card sidenav p-0 border-0 d-none">

    <div class="logo bg-theme-green p-4">
        <a href="/complaints" class="text-decoration-none"><img class="img-fluid px-5 py-2"
                src="{{asset('/assets/website/images/logo-two.png')}}" /></a>
    </div>



    <ul class="list-group list-group-flush mt-4">
        <li class="list-item px-2 @if(Request::segment(1) =='complaints') active @endif p-3">
            <div class="d-flex align-items-center justify-content-between gap-2  cursor-pointer "
                data-bs-toggle="collapse" data-bs-target="#complaints">
                <span class="d-flex align-items-center gap-4"><i
                        class="fa fa-person-chalkboard text-theme-green fa-2x"></i>

                    Complaints </span>
                <i class="fa fa-solid fa-angle-down"></i>
            </div>
            <div class="mt-3 ms-5 collapse" id="complaints" style="">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    <li class="py-3 "><a href="{{route('complaints')}}"
                            class="link-body-emphasis text-start text-decoration-none d-flex gap-3 align-items-center">
                            <i class="fa fa-solid fa-list-check text-theme-green"></i> My
                            Complaints</a>
                    </li>
                    <li class="py-3 "><a href="{{route('complaints.create')}}"
                            class="link-body-emphasis text-start text-decoration-none d-flex gap-3 align-items-center">
                            <i class="fa fa-solid fa-upload text-theme-green"></i>
                            Add New </a></li>
                </ul>
            </div>


        </li>
    </ul>
</div>