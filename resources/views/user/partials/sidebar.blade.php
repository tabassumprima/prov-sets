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
            <li class="{{ Request::is('dashboard') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('user.dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboard">
                        Dashboard
                    </span>
                </a>
            </li>
            @authorize('view-reports', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Reports</span><i
                    data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('report*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('reports') }}">
                    <i data-feather="file-text"></i>
                    <span class="menu-title text-truncate" >
                        Reports
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-calender', true)
            <li class="{{ Request::is('calendar') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('calendar.index') }}">
                    <i data-feather="calendar"></i>
                    <span class="menu-title text-truncate">
                        Calendar Overview
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-insurance-portfolio', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Insurance</span><i
                    data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('portfolio/insurance*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('portfolio.index', ['type' => 'insurance']) }}">
                    <i data-feather="figma"></i>
                    <span class="menu-title text-truncate" >
                        Portfolios
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-insurance-portfolio-criteria', true)
            <li class="{{ Request::is('criteria/insurance*') || session('type') === 'insurance' ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('criteria.index', ['type' => 'insurance']) }}">
                    <i data-feather="framer"></i>
                    <span class="menu-title text-truncate" >
                        Portfolio Criteria
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-insurance-group', true)
            <li class="{{ Request::is('group/insurance*') || Request::is('groups/*/products/create') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('group.index', ['type' => 'insurance']) }}">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate" >
                        Groups
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-re-insurance-portfolio', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Re-Insurance</span><i
                    data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('portfolio/re-insurance*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('portfolio.index', ['type' => 're-insurance']) }}">
                    <i data-feather="figma"></i>
                    <span class="menu-title text-truncate" >
                        Portfolios
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-re-insurance-portfolio-criteria', true)
            <li class="{{ Request::is('criteria/re-insurance*')  ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('criteria.index', ['type' => 're-insurance']) }}">
                    <i data-feather="framer"></i>
                    <span class="menu-title text-truncate" >
                        Portfolio Criteria
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-re-insurance-group', true)
            <li class="{{ Request::is('group/re-insurance*') || Request::is('groups/*/re-insurance*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('group.index', ['type' => 're-insurance']) }}">
                    <i data-feather="layers"></i>
                    <span class="menu-title text-truncate" >
                        Groups
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-summary', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">System Provision</span><i
                    data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('summaries') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('summaries.index') }}">
                    <i data-feather="trending-down"></i>
                    <span class="menu-title text-truncate" >
                        Summaries
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-approve-entry', true)
            <li class="{{ Request::is('approve-entries') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('approveEntry.index') }}">
                    <i data-feather="trending-down"></i>
                    <span class="menu-title text-truncate" >
                        Approve Entries
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-provision-setting', true)
            <li class="{{ Request::is('provision-setting*') || Request::is('provision-mapping*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('provision-setting.index') }}">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate" >
                        Provision Setting
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-provision-output', true)
            <li class="{{ Request::is('provisions*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('provisions.index') }}">
                    <i data-feather="book-open"></i>
                    <span class="menu-title text-truncate" >
                        Provision Output
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-provision-output', true)
            <li class="{{ Request::is('data-import*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('data-import.index') }}">
                    <i data-feather="book-open"></i>
                    <span class="menu-title text-truncate" >
                        Data Import
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-discount-rate', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Actuarial Assumption</span><i
                data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('discount-rates*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('discount-rates.index') }}">
                    <i data-feather="arrow-up-right"></i>
                    <span class="menu-title text-truncate" >
                        Discount Rate
                    </span>
                </a>
            </li>
            @endauthorize
           @authorize('view-discount-rate-gmm', true)
             <li class="{{ Request::is('gmm-discount-rates*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('discount-rates-gmm.index') }}">
                    <i data-feather="arrow-up-right"></i>
                    <span class="menu-title text-truncate" >
                       GMM Inputs
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-ibnr-assumption', true)
            <li class="{{ Request::is('ibnr-assumptions*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('ibnr-assumptions.index') }}">
                    <i data-feather="command"></i>
                    <span class="menu-title text-truncate" >
                        IBNR Assumption
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-risk-adjustment', true)
            <li class="{{ Request::is('risk-adjustments*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('risk-adjustments.index') }}">
                    <i data-feather="loader"></i>
                    <span class="menu-title text-truncate" >
                        Risk Adjustment
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-claim-pattern', true)
            <li class="{{ Request::is('claim-patterns*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('claim-patterns.index') }}">
                    <i data-feather="book-open"></i>
                    <span class="menu-title text-truncate" >
                        Claim Pattern
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('create-journal-entry', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Accounting</span><i
                    data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('journal-entries/create') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('journal-entries.create') }}">
                    <i data-feather="book-open"></i>
                    <span class="menu-title text-truncate" >
                        Journal Entry
                    </span>
                </a>
            </li>
            @endauthorize
            {{-- @authorize('view-journal-entry', true)
            <li class="{{ Request::is('journal-entries') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('journal-entries.index') }}">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate" >
                        Approve Journal Entry
                    </span>
                </a>
            </li>
            @endauthorize --}}
            @authorize('view-general-ledger', true)
            <li class="{{ Request::is('general-ledger') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('general-ledger.index') }}">
                    <i data-feather="briefcase"></i>
                    <span class="menu-title text-truncate" >
                        General Ledger
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-trial-balance', true)
            <li class="{{ Request::is('trial-balance') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('trial-balance.index') }}">
                    <i data-feather="book"></i>
                    <span class="menu-title text-truncate" >
                        Trial Balance
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-chart-of-account', true)
            <li class="{{ Request::is('chart-of-accounts') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('chart-of-accounts.index') }}">
                    <i data-feather="codepen"></i>
                    <span class="menu-title text-truncate" >
                        Chart Of Accounts
                    </span>
                </a>
            </li>
            @endauthorize
            @authorize('view-report-issue', true)
            <li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Miscellaneous</span><i
                data-feather="more-horizontal"></i>
            </li>
            <li class="{{ Request::is('tickets*') ? 'active' : '' }} nav-item">
                <a class="d-flex align-items-center" href="{{ route('tickets.index') }}">
                    <i data-feather="alert-triangle"></i>
                    <span class="menu-title text-truncate" >
                        Report Issue
                    </span>
                </a>
            </li>
            @endauthorize

        </ul>
    </div>
</div>
