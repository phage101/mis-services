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
            color: #002147;
            text-transform: uppercase;
        }

        .portal-header p {
            font-size: 20px;
            color: #002147;
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
                            <div class="service-card">
                                <i class="mdi mdi-ticket-confirmation text-info"></i>
                                <h4>Service Desk</h4>
                                <p>Request technical support or government services easily.</p>
                                <div class="mt-4">
                                    <a href="{{ route('public.tickets.create') }}"
                                        class="btn btn-block btn-lg btn-info mb-2">Access Service</a>
                                    <a href="{{ route('public.tickets.track') }}"
                                        class="btn btn-block btn-outline-info">Track Request</a>
                                </div>
                            </div>
                        </div>

                        <!-- Meeting Request -->
                        <div class="col-md-4 mb-4">
                            <div class="service-card">
                                <i class="mdi mdi-calendar-clock text-success"></i>
                                <h4>Meeting Request</h4>
                                <p>Request and manage your online meeting links easily.</p>
                                <div class="mt-4">
                                    <a href="{{ route('public.meetings.create') }}"
                                        class="btn btn-block btn-lg btn-success mb-2">Access Service</a>
                                    <a href="{{ route('public.meetings.track') }}"
                                        class="btn btn-block btn-outline-success">Track Request</a>
                                </div>
                            </div>
                        </div>

                        <!-- Event Portal -->
                        <div class="col-md-4 mb-4">
                            <div class="service-card">
                                <i class="mdi mdi-calendar-check text-warning"></i>
                                <h4>Event Center</h4>
                                <p>Explore upcoming workshops and register online.</p>
                                <div class="mt-4">
                                    <a href="{{ route('events.index') }}"
                                        class="btn btn-block btn-lg btn-warning">Access Service</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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