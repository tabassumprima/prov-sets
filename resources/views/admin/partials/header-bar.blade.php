    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
            </div>
            @if (Request::has('org'))
                <div class="alert alert-primary mb-0" role="alert">
                    <div class="alert-body">
                        Active: {{ request('org_name') }}
                    </div>
                </div>
            @endif
            <ul class="nav navbar-nav align-items-center ml-auto">
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                            data-feather="moon"></i></a></li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span
                                class="user-name font-weight-bolder">{{ Str::title(Auth::user()->name) }}</span><span
                                class="user-status">Role: {{ Str::title(Auth::user()->roles->first()->name) }}</span>
                        </div>
                        {{-- <span class="avatar">
                            <img class="round" src="{{ asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                alt="avatar" height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span> --}}
                        <span class="avatar-logo">
                            <span class="initial-icon">{{ substr(Str::title(Auth::user()->name), 0, 1) }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <!-- <a class="dropdown-item" href="page-profile.html"><i class="mr-50" data-feather="user"></i> Profile</a> -->
                        <!-- <div class="dropdown-divider"></div> -->
                        @if(auth()->user()->hasRole('admin'))
                            <a class="dropdown-item" href="{{ url('admin/log-viewer') }}"> Log Viewer </a>
                        @endif
                        <!-- <i class="mr-50" data-feather="settings"></i> Settings</a> -->
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                class="mr-50" data-feather="power"></i>Logout</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
