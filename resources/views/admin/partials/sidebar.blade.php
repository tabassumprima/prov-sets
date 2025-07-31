<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="/"><span class="brand-logo">
                        <img class="img-fluid" src="{{ asset('app-assets/images/logo/logo-short.svg') }}"
                            alt="Delta" />
                    </span>
                    <h2 class="brand-text">Delta</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ Request::is('admin/tenant') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('tenant.index') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">
                        Company Tenant
                    </span>
                </a>
            </li>
            @if (Request::session()->get('org'))
                <li class=" navigation-header">
                    <span data-i18n="Apps &amp; Pages">Organization Wise</span>
                    <i data-feather="more-horizontal"></i>
                </li>
                <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('dashboard.index') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Dashboard">
                            Dashboard
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/users*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Users
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/roles*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Roles
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/chart-of-accounts/create') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('chart-of-accounts.create') }}">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Chart Of Account
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/organizations*') || Request::is('admin/subscriptions*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center"
                        href="{{ Request::has('org') ? route('organizations.edit', [request('org')]) : route('tenant.index') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Edit Organization
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/provision-rules') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('provision-rules.index') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Provision Rules
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/import-detail-configs*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('import-detail-configs.index') }}">
                        <i data-feather="upload"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Import Details
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/lambda') || Request::is('admin/lambda/*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('lambda.index') }}">
                        <i data-feather="refresh-ccw"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Lambda
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/lambda-sub-functions*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('lambda-sub-functions.index') }}">
                        <i data-feather="code"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Lambda Sub Functions
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/report-format*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('report-format.index') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Json Format
                        </span>
                    </a>
                </li>
                <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#"><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Card">Setting</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('admin/settings/create') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('settings.create') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>
                            <span class="menu-title text-truncate" data-i18n="Email">
                                General Settings
                            </span>
                            </a>
                        </li>
                        <li class="{{ Request::is('admin/cloud-settings') ? 'active' : '' }} ">
                            <a class="d-flex align-items-center" href="{{ route('cloud.setting') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>
                            <span class="menu-title text-truncate" data-i18n="Email">
                                Cloud Settings
                            </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ Request::is('admin/module/status') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('module.status') }}">
                        <i data-feather="circle"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Module Status
                        </span>
                    </a>
                </li>
            {{-- ------------------------------------------- --}}
            @else
                <li class=" navigation-header">
                    <span data-i18n="Apps &amp; Pages">Generic</span>
                    <i data-feather="more-horizontal"></i>
                </li>
                <li class="{{ Request::is('admin/currencies*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('currencies.index') }}">
                        <i data-feather="dollar-sign"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Currencies
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/countries*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('countries.index') }}">
                        <i data-feather="dribbble"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Country
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/db_config*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('db_config.index') }}">
                        <i data-feather="code"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Database Config
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/user_ticket*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('user_ticket.index') }}">
                        <i data-feather="check-circle"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            User Tickets
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/admin-users*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('admin-users.index') }}">
                        <i data-feather="users"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            Admin Users
                        </span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/organizations*') ? 'active' : '' }} nav-item">
                    <a class="d-flex align-items-center" href="{{ route('organizations.create') }}">
                        <i data-feather="home"></i>
                        <span class="menu-title text-truncate" data-i18n="Email">
                            New Organization
                        </span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</div>
