<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>@yield('title', 'MIS Services')</title>

    <!-- NiceAdmin CSS -->
    <link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- Main wrapper -->
    <div id="main-wrapper">
        <!-- Topbar header -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>
                    <div class="navbar-brand">
                        <a href="{{ url('/dashboard') }}" class="logo">
                            <b class="logo-icon">
                                <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage"
                                    class="dark-logo" />
                                <img src="{{ asset('assets/images/logo-light-icon.png') }}" alt="homepage"
                                    class="light-logo" />
                            </b>
                            <span class="logo-text">
                                <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage"
                                    class="dark-logo" />
                                <img src="{{ asset('assets/images/logo-light-text.png') }}" class="light-logo"
                                    alt="homepage" />
                            </span>
                        </a>
                        <a class="sidebartoggler d-none d-md-block" href="javascript:void(0)"
                            data-sidebartype="mini-sidebar">
                            <i class="mdi mdi-toggle-switch mdi-toggle-switch-off font-20"></i>
                        </a>
                    </div>
                </div>

                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto">

                    </ul>
                    <ul class="navbar-nav float-right">

                        <!-- Desktop Account Dropdown -->
                        <li class="nav-item dropdown d-none d-md-block">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href=""
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="m-l-5 font-medium">{{ Auth::user()->name }}
                                    <i class="mdi mdi-chevron-down"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <a class="dropdown-item" href="{{ route('profile.show') }}"><i
                                        class="ti-user m-r-5 m-l-5"></i>
                                    My Profile</a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i
                                            class="fa fa-power-off m-r-5 m-l-5"></i> Logout</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left Sidebar -->
        <aside class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                                class="hide-menu">General</span></li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('dashboard') }}"
                                aria-expanded="false">
                                <i class="mdi mdi-av-timer"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Core
                                Services</span></li>
                        @can('tickets.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('tickets.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-ticket-confirmation"></i>
                                    <span class="hide-menu">Tickets</span>
                                </a>
                            </li>
                        @endcan
                        @can('meetings.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('meetings.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-calendar-clock"></i>
                                    <span class="hide-menu">Meeting Requests</span>
                                </a>
                            </li>
                        @endcan
                        @can('events.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('events.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-calendar-check"></i>
                                    <span class="hide-menu">Events</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span
                                class="hide-menu">Administration</span></li>
                        @can('users.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('users.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-account-multiple"></i>
                                    <span class="hide-menu">Users</span>
                                </a>
                            </li>
                        @endcan
                        @can('roles.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('roles.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-security"></i>
                                    <span class="hide-menu">Roles</span>
                                </a>
                            </li>
                        @endcan
                        @can('offices.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('offices.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-city"></i>
                                    <span class="hide-menu">Offices</span>
                                </a>
                            </li>
                        @endcan
                        @can('divisions.list')
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('divisions.index') }}" aria-expanded="false">
                                    <i class="mdi mdi-source-branch"></i>
                                    <span class="hide-menu">Divisions</span>
                                </a>
                            </li>
                        @endcan

                        <li class="nav-small-cap d-block d-md-none"><i class="mdi mdi-dots-horizontal"></i> <span
                                class="hide-menu">Account</span></li>
                        <li class="sidebar-item d-block d-md-none">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('profile.show') }}" aria-expanded="false">
                                <i class="ti-user"></i>
                                <span class="hide-menu">My Profile</span>
                            </a>
                        </li>
                        <li class="sidebar-item d-block d-md-none">
                            <form action="{{ route('logout') }}" method="POST" id="mobile-logout-form">
                                @csrf
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="javascript:void(0)"
                                    onclick="document.getElementById('mobile-logout-form').submit();"
                                    aria-expanded="false">
                                    <i class="fa fa-power-off"></i>
                                    <span class="hide-menu">Logout</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">@yield('title')</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @yield('content')
            </div>

            <footer class="footer text-center">
                All Rights Reserved by MIS Services.
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    @stack('scripts')
</body>

</html>