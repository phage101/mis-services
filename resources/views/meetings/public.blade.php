<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Request an Online Meeting - MIS Services</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>

    <style>
        .page-wrapper {
            background: url("{{ asset('assets/images/big/auth-bg.jpg') }}") no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .form-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            overflow: hidden;
        }

        .form-header {
            background: #28a745;
            /* Success color for meetings */
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h2 {
            margin: 0;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-body {
            padding: 40px;
        }

        .section-title {
            color: #28a745;
            font-weight: 700;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .btn-success-green {
            background: #28a745;
            border-color: #28a745;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-success-green:hover {
            background: #218838;
            color: white;
        }

        #new-user-fields {
            display: none;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px dashed #dee2e6;
        }

        .slot-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            position: relative;
        }

        .remove-slot {
            position: absolute;
            right: 15px;
            top: 15px;
            color: #f62d51;
            cursor: pointer;
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>

        <div class="page-wrapper">
            <div class="form-card">
                <div class="form-header">
                    <h2>Meeting Request Form</h2>
                    <p class="mb-0 text-white-50">Request an online meeting link and host support</p>
                </div>

                <div class="form-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('public.meetings.store') }}" method="POST" id="public-meeting-form">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                        <!-- Identity Section -->
                        <h5 class="section-title"><i class="mdi mdi-account-circle mr-2"></i> Your Identity</h5>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                            <select id="requestor_search" class="form-control" required></select>
                            <input type="hidden" name="requestor_id" id="requestor_id">
                            <input type="hidden" name="requestor_name" id="requestor_name_hidden">
                            <small class="form-text text-muted">Type your name to search. If you are not in the list,
                                just type your full name and press Enter.</small>
                        </div>

                        <div id="new-user-fields">
                            <p class="text-info mb-3 small"><i class="mdi mdi-information-outline"></i> New user
                                detected. Please provide these details once.</p>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="font-weight-bold">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="your@email.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">Office <span class="text-danger">*</span></label>
                                    <select name="office_id" id="office_id" class="form-control">
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">Division <span class="text-danger">*</span></label>
                                    <select name="division_id" id="division_id" class="form-control" disabled>
                                        <option value="">Select Division</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Meeting Details -->
                        <h5 class="section-title"><i class="mdi mdi-calendar-text mr-2"></i> Meeting Information</h5>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold">Meeting Topic / Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="topic" class="form-control"
                                placeholder="e.g. Project Alignment Meeting" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Description / Agenda</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Briefly describe the purpose of the meeting..."></textarea>
                        </div>

                        <!-- Slots Section -->
                        <h5 class="section-title d-flex justify-content-between align-items-center">
                            <span><i class="mdi mdi-clock-outline mr-2"></i> Proposed Slots</span>
                            <button type="button" class="btn btn-sm btn-outline-success" id="add-slot">
                                <i class="mdi mdi-plus"></i> Add Another Slot
                            </button>
                        </h5>

                        <div id="slots-container">
                            <div class="slot-item">
                                <div class="row">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="small font-weight-bold">Date</label>
                                        <input type="date" name="slots[0][date]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="small font-weight-bold">Start Time</label>
                                        <input type="time" name="slots[0][start]" class="form-control" value="08:00"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small font-weight-bold">End Time</label>
                                        <input type="time" name="slots[0][end]" class="form-control" value="09:00"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('home') }}" class="text-muted"><i class="mdi mdi-arrow-left"></i> Back to
                                Portal</a>
                            <button type="submit" class="btn btn-success-green"><i class="mdi mdi-send mr-2"></i> Submit
                                Meeting Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".preloader").fadeOut();

            // Identity logic
            $('#requestor_search').select2({
                theme: 'bootstrap4',
                placeholder: 'Search for your name...',
                tags: true,
                ajax: {
                    url: "{{ route('api.public.users.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) { return { q: params.term }; },
                    processResults: function (data) { return { results: data.results }; },
                    cache: true
                },
                minimumInputLength: 3,
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') return null;
                    return { id: term, text: term, isNew: true };
                }
            });

            $('#requestor_search').on('select2:select', function (e) {
                var data = e.params.data;
                $('#requestor_name_hidden').val(data.text);
                if (data.isNew || isNaN(data.id)) {
                    $('#requestor_id').val('');
                    $('#new-user-fields').slideDown();
                    $('#email, #office_id, #division_id').prop('required', true);
                } else {
                    $('#requestor_id').val(data.id);
                    $('#new-user-fields').slideUp();
                    $('#email, #office_id, #division_id').prop('required', false);
                }
            });

            // Slots logic
            let slotIndex = 1;
            $('#add-slot').click(function () {
                const newSlot = `
                    <div class="slot-item animated fadeIn">
                        <i class="mdi mdi-close-circle remove-slot"></i>
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="small font-weight-bold">Date</label>
                                <input type="date" name="slots[${slotIndex}][date]" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="small font-weight-bold">Start Time</label>
                                <input type="time" name="slots[${slotIndex}][start]" class="form-control" value="08:00" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold">End Time</label>
                                <input type="time" name="slots[${slotIndex}][end]" class="form-control" value="09:00" required>
                            </div>
                        </div>
                    </div>
                `;
                $('#slots-container').append(newSlot);
                slotIndex++;
            });

            $(document).on('click', '.remove-slot', function () {
                $(this).closest('.slot-item').fadeOut(function () {
                    $(this).remove();
                });
            });

            // Division logic
            $('#office_id').on('change', function () {
                var officeId = $(this).val();
                var $divSelect = $('#division_id');
                $divSelect.empty().append('<option value="">Select Division</option>').prop('disabled', true);
                if (officeId) {
                    var url = "{{ route('api.public.divisions', ':id') }}".replace(':id', officeId);
                    $.get(url, function (data) {
                        $.each(data, function (i, div) {
                            $divSelect.append('<option value="' + div.id + '">' + div.name + '</option>');
                        });
                        $divSelect.prop('disabled', false);
                    });
                }
            });

            // reCAPTCHA Submission
            $('#public-meeting-form').submit(function (e) {
                e.preventDefault();
                var form = this;
                grecaptcha.ready(function () {
                    grecaptcha.execute("{{ config('services.recaptcha.site') }}", { action: 'submit_meeting' }).then(function (token) {
                        $('#g-recaptcha-response').val(token);
                        form.submit();
                    });
                });
            });
        });
    </script>
</body>

</html>