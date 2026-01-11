<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - {{ $event->title }}</title>
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            color: #3e5569;
        }

        .registration-card {
            max-width: 800px;
            margin: 40px auto;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
        }

        .event-header {
            background: #3e5569;
            color: #fff;
            padding: 40px 30px;
            position: relative;
        }

        .event-header h2 {
            color: #fff;
            font-weight: 700;
        }

        .event-banner-reg {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .form-container {
            padding: 40px 50px;
            background: white;
        }

        .btn-register {
            background: #3e5569;
            border: none;
            padding: 14px 40px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            color: #fff;
            box-shadow: 0 4px 15px rgba(62, 85, 105, 0.3);
        }

        .btn-register:hover {
            background: #2d3e4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(62, 85, 105, 0.4);
            color: #fff;
        }

        .event-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .event-info-item i {
            margin-right: 8px;
            font-size: 1.2rem;
            color: #fff;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            height: auto;
            min-height: 45px;
        }

        select.form-control {
            height: 45px !important;
        }

        .form-control:focus {
            border-color: #3e5569;
            box-shadow: 0 0 0 0.2rem rgba(62, 85, 105, 0.1);
        }

        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card registration-card">
            @if($event->banner_image)
                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Event Banner" class="event-banner-reg">
            @endif

            <div class="event-header text-center">
                <span class="badge badge-light mb-2"><i class="mdi mdi-clipboard-check"></i> Attendance Form</span>
                <h2 class="mb-3">{{ $event->title }}</h2>
                <div class="row justify-content-center mb-3">
                    <div class="event-info-item mx-3">
                        <i class="mdi mdi-map-marker"></i> {{ $event->venue_platform }}
                        ({{ ucfirst($event->venue_type) }})
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless text-white small mb-0">
                                <tbody>
                                    @foreach($event->dates as $eventDate)
                                        <tr style="background: transparent;">
                                            <td class="text-right py-1"
                                                style="width: 50%; color: rgba(255, 255, 255, 0.9);">
                                                <i class="mdi mdi-calendar mr-1"></i>
                                                {{ $eventDate->date->format('M d, Y') }}
                                                ({{ $eventDate->date->format('D') }})
                                            </td>
                                            <td class="text-left py-1" style="color: rgba(255, 255, 255, 0.9);">
                                                <i class="mdi mdi-clock-outline mr-1"></i>
                                                {{ \Carbon\Carbon::parse($eventDate->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($eventDate->end_time)->format('h:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-container">
                @if(session('success'))
                    <div class="text-center py-4">
                        <div class="mb-4">
                            <i class="mdi mdi-check-circle" style="font-size: 60px; color: #3e5569;"></i>
                        </div>
                        <h3 class="font-weight-bold text-dark">Attendance Recorded!</h3>
                        <p class="text-muted mb-4">{{ session('success') }}</p>
                        <a href="{{ route('events.attend', $event) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-account-plus"></i> Add Another Attendee
                        </a>
                    </div>
                @else
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h4 class="text-dark mb-4"><i class="mdi mdi-account-check mr-2"></i>Mark Your Attendance</h4>

                    <form action="{{ route('events.attend.submit', $event) }}" method="POST" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="first_name" required
                                        value="{{ old('first_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="last_name" required
                                        value="{{ old('last_name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Organization</label>
                                    <input type="text" class="form-control" name="organization"
                                        value="{{ old('organization') }}" placeholder="Company/School/Agency">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Designation/Position</label>
                                    <input type="text" class="form-control" name="designation"
                                        value="{{ old('designation') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Sex</label>
                                    <select class="form-control" name="sex">
                                        <option value="">-- Select --</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Age Bracket</label>
                                    <select class="form-control" name="age_bracket">
                                        <option value="">-- Select --</option>
                                        @foreach(['Below 18', '18-24', '25-34', '35-44', '45-54', '55-64', '65 and above'] as $age)
                                            <option value="{{ $age }}" {{ old('age_bracket') == $age ? 'selected' : '' }}>
                                                {{ $age }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Province</label>
                                    <select class="form-control" name="province">
                                        <option value="">-- Select --</option>
                                        @foreach(['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental', 'Others'] as $prov)
                                            <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>
                                                {{ $prov }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Client Type</label>
                                    <select class="form-control" name="client_type">
                                        <option value="">-- Select --</option>
                                        <option value="Citizen" {{ old('client_type') == 'Citizen' ? 'selected' : '' }}>
                                            Citizen</option>
                                        <option value="Business" {{ old('client_type') == 'Business' ? 'selected' : '' }}>
                                            Business</option>
                                        <option value="Government" {{ old('client_type') == 'Government' ? 'selected' : '' }}>
                                            Government</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Contact No.</label>
                                    <input type="text" class="form-control" name="contact_no"
                                        value="{{ old('contact_no') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Email Address</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-group">
                            <label class="font-weight-bold">Session/Date <span class="text-danger">*</span></label>
                            <select class="form-control" name="event_date_id" required>
                                @foreach($event->dates as $date)
                                    <option value="{{ $date->id }}" {{ $date->date->isToday() ? 'selected' : '' }}>
                                        {{ $date->date->format('F d, Y') }} ({{ $date->date->format('l') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-register btn-lg">
                                <i class="mdi mdi-check mr-1"></i> Submit Attendance
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="card-footer text-center py-3">
                <small class="text-muted">Powered by DTI MIS Services</small>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script>
        grecaptcha.ready(function () {
            $('#attendanceForm').on('submit', function (e) {
                e.preventDefault();
                let form = this;
                grecaptcha.execute('{{ config('services.recaptcha.site') }}', { action: 'attendance' }).then(function (token) {
                    $('#recaptchaResponse').val(token);
                    form.submit();
                });
            });
        });
    </script>
</body>

</html>