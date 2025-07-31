    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
            </div>

            <div class="alert alert-primary align-items-center mb-0 provision-alert d-none" data-toggle="tooltip"
                data-html="true" data-placement="bottom" title="{{session('valuation_date')}}" role="alert">
                <div class="alert-body" style="display: flex;">
                    <a href="#" style="color: inherit;margin-right: 5px;"></a>
                    <div class="sk-wave sk-primary">
                        <div class="sk-wave-rect"></div>
                        <div class="sk-wave-rect"></div>
                        <div class="sk-wave-rect"></div>
                        <div class="sk-wave-rect"></div>
                        <div class="sk-wave-rect"></div>
                    </div>

                </div>
            </div>

            @if (session('provision_alert'))
                <div class="alert alert-warning align-items-center mb-0 unapprove-notification" role="alert">
                    <div class="alert-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                            role="img" aria-label="Warning:">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                       <x-alert-notification :type="session('provision_alert')" />
                    </div>
                </div>
            @endif


            <ul class="nav navbar-nav align-items-center ml-auto">
                @if (session('impersonated_by'))
                    <li class="nav-item d-none d-lg-block">
                        <a href="{{ route('user.leave-impersonate') }}" class="btn btn-danger">Logout</a>
                    </li>
                @endif
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span
                                class="user-name font-weight-bolder">{{ Str::title(Auth::user()->name) }}</span><span
                                class="user-status">Role:
                                {{ Str::title(Auth::user()->roles->first()->name) }}</span></div>
                        <span class="avatar-logo">
                            <span class="initial-icon">{{ substr(Str::title(Auth::user()->name), 0, 1) }}</span>
                        </span>
                        {{-- <span class="avatar"><img class="round"
                                src="{{ asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar"
                                height="40" width="40"><span class="avatar-status-online"></span></span> --}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                        <a class="dropdown-item"
                            href="{{ route('user.edit', ['user' => CustomHelper::encode(Auth::user()->id)]) }}">
                            <i class="mr-50" data-feather="lock"></i>Change Password</a>
                        {{-- <a class="dropdown-item" href="">
                        <i class="mr-50" data-feather="settings"></i>Settings</a> --}}
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
