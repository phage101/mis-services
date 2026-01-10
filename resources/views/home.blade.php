<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MIS Services Portal - Home</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <style>
        .home-wrapper {
            background: url("{{ asset('assets/images/big/auth-bg.jpg') }}") no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .service-card {
            background: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 40px 25px;
            text-align: center;
            transition: all 0.3s ease;
            color: #3e5569;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-decoration: none !important;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        }

        .service-card i {
            font-size: 56px;
            margin-bottom: 20px;
            display: block;
        }

        .service-card h4 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #3e5569;
        }

        .service-card p {
            color: #747d8a;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .portal-header {
            padding: 60px 0 30px;
            text-align: center;
        }

        .portal-header h1 {
            font-size: 48px;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 15px;
            color: #ffffff;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
        }

        .portal-header p {
            font-size: 20px;
            color: #ffffff;
            max-width: 650px;
            margin: 0 auto;
            font-weight: 400;
        }

        .top-nav {
            padding: 20px 50px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: transparent;
        }

        .navbar-brand img {
            max-height: 35px;
        }

        .nav-btn {
            background: #ffffff;
            border: 1px solid #ffffff;
            color: #1e88e5 !important;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .nav-btn:hover {
            background: #f8f9fa;
            transform: scale(1.02);
        }

        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-bottom: 60px;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <!-- Preloader -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div class="home-wrapper">
            <nav class="top-nav">
                <div class="navbar-brand mr-auto">
                    <a href="{{ url('/') }}" class="logo d-flex align-items-center">
                        <b class="logo-icon">
                            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="light-logo" />
                        </b>
                        <span class="logo-text ml-2">
                            <img src="{{ asset('assets/images/logo-light-text.png') }}" class="light-logo"
                                alt="homepage" />
                        </span>
                    </a>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-btn"><i class="mdi mdi-view-dashboard"></i> Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn"><i class="mdi mdi-login"></i> Sign In</a>
                @endauth
            </nav>

            <div class="main-container">
                <div class="portal-header">
                    <h1>MIS SERVICES PORTAL</h1>
                    <p>Select a service below to get started with our integrated platform.</p>
                </div>

                <div class="container">
                    <div class="row">
                        <!-- Ticket Service -->
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('tickets.index') }}" class="service-card">
                                <i class="mdi mdi-ticket-confirmation text-info"></i>
                                <h4>Service Desk</h4>
                                <p>Request technical support or government services easily.</p>
                                <div class="mt-4">
                                    <span class="btn btn-block btn-lg btn-info">Access Service</span>
                                </div>
                            </a>
                        </div>

                        <!-- Meeting Hub -->
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('meetings.index') }}" class="service-card">
                                <i class="mdi mdi-calendar-clock text-success"></i>
                                <h4>Meeting Hub</h4>
                                <p>Schedule and manage your video conferences efficiently.</p>
                                <div class="mt-4">
                                    <span class="btn btn-block btn-lg btn-success">Access Service</span>
                                </div>
                            </a>
                        </div>

                        <!-- Event Portal -->
                        <div class="col-md-4 mb-4">
                            <a href="{{ route('events.index') }}" class="service-card">
                                <i class="mdi mdi-calendar-check text-warning"></i>
                                <h4>Event Center</h4>
                                <p>Explore upcoming workshops and register online.</p>
                                <div class="mt-4">
                                    <span class="btn btn-block btn-lg btn-warning">Access Service</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="text-center p-4" style="color: rgba(255,255,255,0.6); background: rgba(0,0,0,0.2);">
                &copy; {{ date('Y') }} MIS Services Portal. All Rights Reserved.
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(".preloader").fadeOut();
    </script>
</body>

</html>