<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for {{ $event->title }}</title>
    <!-- Nice Admin CSS from the project -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <!-- reCAPTCHA v3 -->
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
            background: #273444;
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
            background: #273444;
            border: none;
            padding: 14px 40px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            color: #fff;
            box-shadow: 0 4px 15px rgba(39, 52, 68, 0.3);
        }

        .btn-register:hover {
            background: #1e2936;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 52, 68, 0.4);
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

        .privacy-statement {
            background: #f1f2f6;
            padding: 20px;
            border-radius: 8px;
            font-size: 0.85rem;
            line-height: 1.6;
            border-left: 4px solid #273444;
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
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .form-control:focus {
            border-color: #273444;
            box-shadow: 0 0 0 0.2rem rgba(39, 52, 68, 0.1);
        }

        label {
            color: #3e5569;
            margin-bottom: 8px;
        }

        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .custom-control-label {
            padding-top: 2px;
            cursor: pointer;
        }

        h4.text-dark {
            color: #54667a !important;
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
                            <i class="mdi mdi-check-circle text-success" style="font-size: 60px;"></i>
                        </div>
                        <h3 class="font-weight-bold text-dark">Registration Successful!</h3>
                        <p class="text-muted mb-4">{{ session('success') }}</p>

                        @if(session('participant_uuid'))
                            <div class="card border shadow-sm mx-auto mb-4" style="max-width: 300px;">
                                    <div class="mb-3 p-3 bg-white border rounded d-inline-block shadow-sm" id="participantQrContainer">
                                        {!! QrCode::size(200)->margin(1)->generate(route('events.attendance.mark', [$event, session('participant_uuid')])) !!}
                                    </div>
                                    <p class="small text-muted mb-3">{{ session('participant_name') }}</p>
                                    <button class="btn btn-sm btn-info w-100" onclick="downloadParticipantQR()">
                                        <i class="mdi mdi-download mr-1"></i> Save QR Code
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-info small mb-4">
                                <i class="mdi mdi-information-outline"></i> Please save this QR code and present it at the venue
                                for attendance check-in.
                            </div>
                        @endif

                        <a href="{{ route('events.register', $event) }}" class="btn btn-outline-secondary">Register Another
                            Participant</a>
                    </div>
                    <script>
                        function downloadParticipantQR() {
                            const svg = document.querySelector('#participantQrContainer svg');
                            const svgData = new XMLSerializer().serializeToString(svg);
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            const img = new Image();
                            
                            img.onload = function() {
                                canvas.width = img.width + 40;
                                canvas.height = img.height + 40;
                                ctx.fillStyle = 'white';
                                ctx.fillRect(0, 0, canvas.width, canvas.height);
                                ctx.drawImage(img, 20, 20);
                                const pngFile = canvas.toDataURL('image/png');
                                const downloadLink = document.createElement('a');
                                downloadLink.download = 'attendance-qr-{{ session("participant_name") }}.png';
                                downloadLink.href = pngFile;
                                downloadLink.click();
                            };
                            
                            img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                        }
                    </script>
                @else
                    <h4 class="mb-4 font-weight-bold text-dark border-bottom pb-3">Registration Form</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('events.register.submit', $event) }}" method="POST" id="registration-form">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                        <div class="row">
                            @php
                                $enabledFields = $event->registration_fields ?? [];
                            @endphp

                            @if(in_array('firstname', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Enter first name"
                                        required value="{{ old('first_name') }}">
                                </div>
                            @endif

                            @if(in_array('lastname', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Enter last name"
                                        required value="{{ old('last_name') }}">
                                </div>
                            @endif

                            @if(in_array('email', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="email@example.com"
                                        required value="{{ old('email') }}">
                                </div>
                            @endif

                            @if(in_array('contact_no', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_no" class="form-control" placeholder="e.g. 09123456789"
                                        required value="{{ old('contact_no') }}">
                                </div>
                            @endif

                            @if(in_array('organization', $enabledFields))
                                <div class="col-md-12 mb-4 text-left">
                                    <label class="font-weight-bold">Organization / Office / School <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="organization" class="form-control" placeholder="Entity name"
                                        required value="{{ old('organization') }}">
                                </div>
                            @endif

                            @if(in_array('designation', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Designation / Position <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="designation" class="form-control"
                                        placeholder="e.g. Manager, Student, etc." required value="{{ old('designation') }}">
                                </div>
                            @endif

                            @if(in_array('province', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Province <span class="text-danger">*</span></label>
                                    <select name="province" class="form-control" required>
                                        <option value="">Select province</option>
                                        <option value="Aklan" {{ old('province') == 'Aklan' ? 'selected' : '' }}>Aklan</option>
                                        <option value="Antique" {{ old('province') == 'Antique' ? 'selected' : '' }}>Antique
                                        </option>
                                        <option value="Capiz" {{ old('province') == 'Capiz' ? 'selected' : '' }}>Capiz</option>
                                        <option value="Guimaras" {{ old('province') == 'Guimaras' ? 'selected' : '' }}>Guimaras
                                        </option>
                                        <option value="Iloilo" {{ old('province') == 'Iloilo' ? 'selected' : '' }}>Iloilo</option>
                                        <option value="Negros Occidental" {{ old('province') == 'Negros Occidental' ? 'selected' : '' }}>Negros Occidental</option>
                                        <option value="Others" {{ old('province') == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                            @endif

                            @if(in_array('sex', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Sex <span class="text-danger">*</span></label>
                                    <select name="sex" class="form-control" required>
                                        <option value="">Select sex</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            @endif

                            @if(in_array('age_bracket', $enabledFields))
                                <div class="col-md-6 mb-4 text-left">
                                    <label class="font-weight-bold">Age Bracket <span class="text-danger">*</span></label>
                                    <select name="age_bracket" class="form-control" required>
                                        <option value="">Select age bracket</option>
                                        <option value="19 or lower" {{ old('age_bracket') == '19 or lower' ? 'selected' : '' }}>19 or lower</option>
                                        <option value="20–34" {{ old('age_bracket') == '20–34' ? 'selected' : '' }}>20–34</option>
                                        <option value="35–49" {{ old('age_bracket') == '35–49' ? 'selected' : '' }}>35–49</option>
                                        <option value="50–64" {{ old('age_bracket') == '50–64' ? 'selected' : '' }}>50–64</option>
                                        <option value="65 or higher" {{ old('age_bracket') == '65 or higher' ? 'selected' : '' }}>65 or higher</option>
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6 mb-4 text-left">
                                <label class="font-weight-bold">Client Type <span class="text-danger">*</span></label>
                                <select name="client_type" class="form-control" required>
                                    <option value="">Select client type</option>
                                    <option value="Citizen" {{ old('client_type') == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                    <option value="Business" {{ old('client_type') == 'Business' ? 'selected' : '' }}>Business</option>
                                    <option value="Government Employee or other agency" {{ old('client_type') == 'Government Employee or other agency' ? 'selected' : '' }}>Government Employee or other agency</option>
                                </select>
                            </div>

                            @foreach($event->formFields as $field)
                                <div class="col-md-12 mb-4 text-left">
                                    <label class="font-weight-bold">{{ $field->label }}
                                        @if($field->is_required)<span class="text-danger">*</span>@endif</label>

                                    @if($field->field_type == 'text')
                                        <input type="text" name="custom_fields[{{ $field->id }}]" class="form-control" {{ $field->is_required ? 'required' : '' }}
                                            value="{{ old('custom_fields.' . $field->id) }}">

                                    @elseif($field->field_type == 'textarea')
                                        <textarea name="custom_fields[{{ $field->id }}]" class="form-control" rows="3" {{ $field->is_required ? 'required' : '' }}>{{ old('custom_fields.' . $field->id) }}</textarea>

                                    @elseif($field->field_type == 'select')
                                        <select name="custom_fields[{{ $field->id }}]" class="form-control" {{ $field->is_required ? 'required' : '' }}>
                                            <option value="">Select an option</option>
                                            @foreach($field->options as $option)
                                                <option value="{{ trim($option) }}" {{ old('custom_fields.' . $field->id) == trim($option) ? 'selected' : '' }}>{{ trim($option) }}</option>
                                            @endforeach
                                        </select>

                                    @elseif($field->field_type == 'radio')
                                        <div class="mt-2">
                                            @foreach($field->options as $option)
                                                <div class="custom-control custom-radio mb-2">
                                                    <input class="custom-control-input" type="radio"
                                                        name="custom_fields[{{ $field->id }}]"
                                                        id="field_{{ $field->id }}_{{ $loop->index }}" value="{{ trim($option) }}" {{ $field->is_required ? 'required' : '' }} {{ old('custom_fields.' . $field->id) == trim($option) ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="field_{{ $field->id }}_{{ $loop->index }}">{{ trim($option) }}</label>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif($field->field_type == 'checkbox')
                                        <div class="mt-2 text-left">
                                            @foreach($field->options as $option)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input class="custom-control-input" type="checkbox"
                                                        name="custom_fields[{{ $field->id }}][]"
                                                        id="field_{{ $field->id }}_{{ $loop->index }}" value="{{ trim($option) }}" {{ old('custom_fields.' . $field->id) && in_array(trim($option), old('custom_fields.' . $field->id)) ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="field_{{ $field->id }}_{{ $loop->index }}">{{ trim($option) }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="privacy-statement mb-4 text-left">
                            <h6 class="font-weight-bold mb-2">Data Privacy Statement</h6>
                            <p class="mb-3">By participating in this event and providing your personal information, you
                                consent to the collection, processing, and storage of your data by MIS Services for the
                                purpose of event management, attendance tracking, and future communication regarding related
                                initiatives. We are committed to protecting your privacy and will not share your information
                                with third parties without your explicit consent.</p>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="privacy_consent"
                                    name="privacy_consent" value="1" required>
                                <label class="custom-control-label font-weight-bold" for="privacy_consent">I have read and
                                    agree to the Data Privacy Statement <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-register">
                                <i class="mdi mdi-account-plus mr-1"></i> Submit Registration
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="card-footer text-center py-4">
                <p class="mb-0 text-muted small">&copy; {{ date('Y') }} MIS Services. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // reCAPTCHA Submission
        $('#registration-form').submit(function (e) {
            e.preventDefault();
            var form = this;
            grecaptcha.ready(function () {
                grecaptcha.execute("{{ config('services.recaptcha.site') }}", { action: 'register_event' }).then(function (token) {
                    $('#g-recaptcha-response').val(token);
                    form.submit();
                });
            });
        });
    </script>
</body>

</html>