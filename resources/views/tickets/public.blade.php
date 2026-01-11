<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submit a Request - MIS Services</title>
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
            max-width: 800px;
            overflow: hidden;
        }

        .form-header {
            background: #002147;
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

        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 10px) !important;
            padding-top: 5px;
        }

        .section-title {
            color: #002147;
            font-weight: 700;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .btn-navy {
            background: #002147;
            border-color: #002147;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-navy:hover {
            background: #003366;
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
                    <h2>Service Desk Ticket</h2>
                    <p class="mb-0 text-white-50">Submit your technical request or complaint online</p>
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

                    <form action="{{ route('public.tickets.store') }}" method="POST" id="public-ticket-form">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                        <!-- Identity Section -->
                        <h5 class="section-title"><i class="mdi mdi-account-card-details mr-2"></i> Your Identity</h5>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                            <select id="requestor_search" class="form-control" required></select>
                            <input type="hidden" name="requestor_id" id="requestor_id">
                            <input type="hidden" name="requestor_name" id="requestor_name_hidden">
                            <small class="form-text text-muted">Type your name to search. If you are not in the list,
                                just type your full name and press Enter.</small>
                        </div>

                        <!-- New User Hidden Fields -->
                        <div id="new-user-fields">
                            <p class="text-info mb-3 small"><i class="mdi mdi-information-outline"></i> It looks like
                                you're new! Please provide a few more details so we can create your account.</p>
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
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">Client Type <span
                                            class="text-danger">*</span></label>
                                    <select name="client_type" id="client_type" class="form-control">
                                        <option value="">Select Client Type</option>
                                        <option value="Citizen">Citizen</option>
                                        <option value="Business">Business</option>
                                        <option value="Government Employee or other agency">Government Employee or other
                                            agency</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold">Age Bracket <span
                                            class="text-danger">*</span></label>
                                    <select name="age_bracket" id="age_bracket" class="form-control">
                                        <option value="">Select Age Bracket</option>
                                        <option value="19 or lower">19 or lower</option>
                                        <option value="20–34">20–34</option>
                                        <option value="35–49">35–49</option>
                                        <option value="50–64">50–64</option>
                                        <option value="65 or higher">65 or higher</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Details -->
                        <h5 class="section-title"><i class="mdi mdi-file-document-edit mr-2"></i> Request Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Request Type <span class="text-danger">*</span></label>
                                <select name="request_type_id" id="request_type_id" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach($requestTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required disabled>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">Urgency <span class="text-danger">*</span></label>
                                <select name="urgency" id="urgency" class="form-control" required>
                                    @foreach($urgencies as $val => $label)
                                        <option value="{{ $val }}" {{ $val == 'medium' ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Complaint / Message <span
                                    class="text-danger">*</span></label>
                            <textarea name="complaint" id="complaint" class="form-control" rows="5"
                                placeholder="Describe your issue or request in detail..." required></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('home') }}" class="text-muted"><i class="mdi mdi-arrow-left"></i> Back to
                                Portal</a>
                            <button type="submit" class="btn btn-navy"><i class="mdi mdi-send mr-2"></i> Submit
                                Request</button>
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

            // Initialize Select2 with Tagging (for new names)
            $('#requestor_search').select2({
                theme: 'bootstrap4',
                placeholder: 'Search for your name...',
                tags: true,
                ajax: {
                    url: "{{ route('api.public.users.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                },
                minimumInputLength: 3,
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') return null;
                    return { id: term, text: term, isNew: true };
                }
            });

            // Handle Name Selection
            $('#requestor_search').on('select2:select', function (e) {
                var data = e.params.data;
                if (data.isNew || isNaN(data.id)) {
                    $('#requestor_id').val('');
                    $('#requestor_name_hidden').val(data.text);
                    $('#new-user-fields').slideDown();
                    $('#email, #office_id, #division_id').prop('required', true);
                } else {
                    $('#requestor_id').val(data.id);
                    $('#requestor_name_hidden').val(data.text);
                    $('#new-user-fields').slideUp();
                    $('#email, #office_id, #division_id').prop('required', false);
                }
            });

            // Category Loading
            $('#request_type_id').on('change', function () {
                var typeId = $(this).val();
                var $catSelect = $('#category_id');
                $catSelect.empty().append('<option value="">Select Category</option>').prop('disabled', true);
                if (typeId) {
                    var url = "{{ route('api.public.categories', ':id') }}".replace(':id', typeId);
                    $.get(url, function (data) {
                        $.each(data, function (i, cat) {
                            $catSelect.append('<option value="' + cat.id + '">' + cat.name + '</option>');
                        });
                        $catSelect.prop('disabled', false);
                    });
                }
            });

            // Division Loading
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
            $('#public-ticket-form').submit(function (e) {
                e.preventDefault();
                var form = this;
                grecaptcha.ready(function () {
                    grecaptcha.execute("{{ config('services.recaptcha.site') }}", { action: 'submit_ticket' }).then(function (token) {
                        $('#g-recaptcha-response').val(token);
                        form.submit();
                    });
                });
            });
        });
    </script>
</body>

</html>