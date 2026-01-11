<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events - DTI</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: #3e5569;
        }

        .header-bg {
            background: #3e5569;
            color: #fff;
            padding: 60px 0 40px;
            margin-bottom: 40px;
        }

        .event-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .event-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
            background-color: #e9ecef;
        }

        .event-body {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .event-date-badge {
            display: inline-block;
            background: #e8f0fe;
            color: #3e5569;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .event-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .event-meta {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .event-meta i {
            width: 20px;
            margin-right: 5px;
            color: #3e5569;
        }

        .btn-register {
            background: #3e5569;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            margin-top: auto;
            display: block;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-register:hover {
            background: #2d3e4a;
            color: #fff;
        }

        .search-container {
            max-width: 600px;
            margin: -25px auto 40px;
            background: #fff;
            padding: 10px;
            border-radius: 50px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            display: flex;
        }

        .search-input {
            border: none;
            flex: 1;
            padding: 10px 20px;
            border-radius: 50px;
            outline: none;
        }

        .search-btn {
            background: #3e5569;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="header-bg text-center">
        <div class="container">
            <h1 class="font-weight-bold mb-2">Upcoming Events</h1>
            <p class="opacity-75">Browse and register for our upcoming sessions and seminars</p>
        </div>
    </div>

    <div class="container pb-5">
        <!-- Search Bar (Optional for future functionality, just visual for now) -->
        <!-- <div class="search-container">
            <input type="text" class="search-input" placeholder="Search events...">
            <button class="search-btn"><i class="mdi mdi-magnify"></i></button>
        </div> -->

        @if($events->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="mdi mdi-calendar-blank text-muted" style="font-size: 64px;"></i>
                </div>
                <h3 class="text-muted font-weight-bold">No Upcoming Events</h3>
                <p class="text-muted">Please check back later for new event listings.</p>
            </div>
        @else
            @foreach($events as $month => $monthEvents)
                <h3 class="font-weight-bold mb-4 mt-2 text-dark border-bottom pb-2">{{ $month }}</h3>
                <div class="row mb-5">
                    @foreach($monthEvents as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="event-card">
                                @if($event->banner_image)
                                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}"
                                        class="event-img">
                                @else
                                    <div class="event-img d-flex align-items-center justify-content-center bg-light text-muted">
                                        <i class="mdi mdi-image-off" style="font-size: 48px;"></i>
                                    </div>
                                @endif
                                <div class="event-body">
                                    <div>
                                        <span class="event-date-badge">
                                            <i class="mdi mdi-calendar mr-1"></i>
                                            @if($event->dates->count() > 0)
                                                {{ $event->dates->first()->date->format('M d, Y') }}
                                            @else
                                                TBA
                                            @endif
                                        </span>
                                        <h5 class="event-title">{{ Str::limit($event->title, 50) }}</h5>

                                        <div class="event-meta">
                                            <i class="mdi mdi-map-marker"></i>
                                            {{ Str::limit($event->venue_platform, 40) }}
                                        </div>

                                        <div class="event-meta">
                                            <i class="mdi mdi-clock-outline"></i>
                                            @if($event->dates->count() > 0)
                                                {{ \Carbon\Carbon::parse($event->dates->first()->start_time)->format('h:i A') }}
                                            @else
                                                Time TBA
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('events.register', $event) }}" class="btn-register">
                                            Register Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>