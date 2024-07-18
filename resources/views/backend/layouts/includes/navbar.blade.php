<header class="p-3 bg-dark text-white d-none">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap" />
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                @if(Auth::user()->can('home.index'))
                <li><a href="{{ route('home.index') }}" class="nav-link px-2 text-white">Dashboard</a></li>
                @endif

                @auth
                @if(Auth::user()->can('users.index') || Auth::user()->can('roles.index') ||
                Auth::user()->can('permissions.index') )
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Users
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @if(Auth::user()->can('users.index'))
                        <li><a href="{{ route('users.index') }}" class="dropdown-item">Users</a></li>
                        @endif
                        @if(Auth::user()->can('roles.index'))
                        <li><a href="{{ route('roles.index') }}" class="dropdown-item">Roles</a></li>
                        @endif
                        @if(Auth::user()->can('permissions.index'))
                        <li><a href="{{ route('permissions.index') }}" class="dropdown-item">Permission</a></li>
                        @endif
                    </ul>
                </li>

                @endif
               
              </ul>
            </li>
            @endif

      

            @if(Auth::user()->can('complaints.index'))
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Complaints
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @if(Auth::user()->can('complaints.index'))
                    <li><a href="{{ route('complaints.index') }}" class="dropdown-item">Complaints</a></li>
                    @endif
                    @if(Auth::user()->can('categories.index'))
                    <li><a href="{{ route('categories.index') }}" class="dropdown-item">Categories</a></li>
                    @endif
                </ul>
            </li>
            @endif
          

           

            @if(Auth::user()->can('report-assets') || Auth::user()->can('report-categories') ||
            Auth::user()->can('report-tickets') )
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Reports
                </a>
                <ul class="dropdown-menu">
                    <!-- @if(Auth::user()->can('report-assets'))
                  <li><a href="{{route('report-assets')}}" class="dropdown-item">Assets</a></li>
                @endif

                @if(Auth::user()->can('report-categories'))
                  <li><a href="{{route('report-categories')}}" class="dropdown-item">Category</a></li>
                @endif

                @if(Auth::user()->can('report-tickets'))
                  <li><a href="{{route('report-tickets')}}"class="dropdown-item">Tickets</a></li>
                @endif -->

                    @if(Auth::user()->can('report-tickets'))
                    <li><a href="{{route('report-complains')}}" class="dropdown-item">Complains</a></li>
                    @endif

                </ul>
            </li>
            @endif

          


            </ul>

            <!--<form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
        <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
      </form>-->

            @auth
            {{auth()->user()->name}}&nbsp;
            <div class="text-end">
                <a href="{{ route('logout.perform') }}" class="btn btn-outline-light me-2">Logout</a>
            </div>
            @endauth

        </div>
    </div>
</header>