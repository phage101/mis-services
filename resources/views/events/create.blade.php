@extends('layouts.nice-admin')

@section('title', 'Create New Event')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Event Information</h4>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title">Event Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title') }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description">Description (Optional)</label>
                                    <textarea name="description" id="description" class="form-control"
                                        rows="3">{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="banner_image">Social Media Card / Banner (Optional)</label>
                                    <div class="custom-file">
                                        <input type="file" name="banner_image" id="banner_image" class="custom-file-input">
                                        <label class="custom-file-label" for="banner_image">Choose image...</label>
                                    </div>
                                    <small class="text-muted">Recommended size: 1200x630 (aspect ratio 1.91:1)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="event_type">Category</label>
                                    <select name="event_type" id="event_type" class="form-control">
                                        <option value="Meeting">Meeting</option>
                                        <option value="Workshop">Workshop</option>
                                        <option value="Seminar">Seminar</option>
                                        <option value="Training">Training</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="classification">Classification</label>
                                    <select name="classification" id="classification" class="form-control">
                                        <option value="Internal">Internal</option>
                                        <option value="External">External</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="expected_participants">Expected Participants (Optional)</label>
                                    <input type="number" name="expected_participants" id="expected_participants"
                                        class="form-control" value="{{ old('expected_participants', 0) }}">
                                </div>
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="enable_qr" name="enable_qr"
                                            value="1">
                                        <label class="custom-control-label font-weight-bold" for="enable_qr">
                                            <i class="mdi mdi-qrcode mr-1"></i> Enable QR Code & Email Sending
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1 pl-4">Sends confirmation email with QR code upon
                                        registration.</small>
                                </div>
                                <div class="form-group mt-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="disable_registration"
                                            name="disable_registration" value="1">
                                        <label class="custom-control-label font-weight-bold" for="disable_registration">
                                            <i class="mdi mdi-account-off mr-1"></i> Attendance Only (No Registration)
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1 pl-4">Disables public registration. Only walk-in
                                        attendance tracking.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="venue_type">Venue Type</label>
                                    <select name="venue_type" id="venue_type" class="form-control">
                                        <option value="onsite">Onsite</option>
                                        <option value="online">Online</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="venue_platform">Venue Name / Platform Link</label>
                                    <input type="text" name="venue_platform" id="venue_platform" class="form-control"
                                        placeholder="Room name or Zoom/Google Meet link" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="font-weight-bold">Event Schedule</h5>
                                    <button type="button" class="btn btn-sm btn-outline-info" id="add-date">
                                        <i class="mdi mdi-plus"></i> Add Date
                                    </button>
                                </div>
                                <div id="dates-container">
                                    <div class="date-row mb-3 p-3 bg-light rounded border">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="small font-weight-bold">Date</label>
                                                    <input type="date" name="dates[0][date]" class="form-control"
                                                        value="{{ date('Y-m-d') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="small font-weight-bold">Start Time</label>
                                                    <input type="time" name="dates[0][start_time]" class="form-control"
                                                        value="08:00" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-0">
                                                    <label class="small font-weight-bold">End Time</label>
                                                    <input type="time" name="dates[0][end_time]" class="form-control"
                                                        value="17:00" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted"><i class="mdi mdi-information-outline"></i> You can add multiple
                                    dates for multi-day events or sessions.</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">Standard Registration Fields</h5>
                                <p class="text-muted mb-3"><small>Select which standard fields to include in the
                                        registration form.</small></p>

                                <hr class="my-3">

                                <div class="row mb-4">
                                    @php
                                        $standardFields = [
                                            'firstname' => 'First Name',
                                            'lastname' => 'Last Name',
                                            'organization' => 'Organization (Business/School/etc)',
                                            'designation' => 'Designation/Position',
                                            'age_bracket' => 'Age Bracket',
                                            'sex' => 'Sex',
                                            'province' => 'Province',
                                            'contact_no' => 'Contact No.',
                                            'email' => 'Email Address'
                                        ];
                                    @endphp
                                    @foreach($standardFields as $key => $label)
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="field_{{ $key }}"
                                                    name="registration_fields[]" value="{{ $key }}" checked>
                                                <label class="custom-control-label" for="field_{{ $key }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Registration Form Builder (Custom Fields)</h5>
                                    <button type="button" class="btn btn-sm btn-outline-info" id="add-field">
                                        <i class="mdi mdi-plus"></i> Add Field
                                    </button>
                                </div>
                                <div id="fields-container">
                                    <!-- Dynamic fields will be added here -->
                                </div>
                                <div class="alert alert-light border">
                                    <small class="text-muted">
                                        <i class="mdi mdi-information-outline"></i> Select standard fields above and add any
                                        event-specific custom fields here (e.g. T-Shirt size, Food preference).
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="mdi mdi-content-save"></i> Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let fieldIndex = 0;
            let dateIndex = 1;

            $('#add-date').click(function () {
                let html = `
                                            <div class="date-row mb-3 p-3 bg-light rounded border position-relative">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute remove-date-btn" style="top:-10px; right:-10px;">&times;</button>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-0">
                                                            <label class="small font-weight-bold">Date</label>
                                                            <input type="date" name="dates[${dateIndex}][date]" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-0">
                                                            <label class="small font-weight-bold">Start Time</label>
                                                            <input type="time" name="dates[${dateIndex}][start_time]" class="form-control" value="08:00" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-0">
                                                            <label class="small font-weight-bold">End Time</label>
                                                            <input type="time" name="dates[${dateIndex}][end_time]" class="form-control" value="17:00" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                $('#dates-container').append(html);
                dateIndex++;
            });

            $(document).on('click', '.remove-date-btn', function () {
                $(this).closest('.date-row').remove();
            });

            function addField() {
                let html = `
                                                    <div class="field-row mb-3 p-3 bg-light rounded border position-relative">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute remove-btn" style="top:-10px; right:-10px;">&times;</button>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <label class="small font-weight-bold">Field Label</label>
                                                                <input type="text" name="form_fields[${fieldIndex}][label]" class="form-control" placeholder="e.g. Food Preference" required>
                                                            </div>
                                                            <div class="col-md-3 mb-2">
                                                                <label class="small font-weight-bold">Field Type</label>
                                                                <select name="form_fields[${fieldIndex}][field_type]" class="form-control field-type-select">
                                                                    <option value="text">Short Answer (Text)</option>
                                                                    <option value="textarea">Long Answer (Paragraph)</option>
                                                                    <option value="select">Dropdown</option>
                                                                    <option value="radio">Multiple Choice (Radio)</option>
                                                                    <option value="checkbox">Checkboxes</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3 mb-2 d-flex align-items-center justify-content-center">
                                                                <div class="form-check pt-3">
                                                                    <input class="form-check-input" type="checkbox" name="form_fields[${fieldIndex}][is_required]" id="req_${fieldIndex}" value="1">
                                                                    <label class="form-check-label" for="req_${fieldIndex}">Required?</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 options-container d-none">
                                                                <label class="small font-weight-bold">Options (Comma separated)</label>
                                                                <input type="text" name="form_fields[${fieldIndex}][options]" class="form-control" placeholder="Option 1, Option 2, Option 3">
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                $('#fields-container').append(html);
                fieldIndex++;
            }

            $('#add-field').click(addField);

            $(document).on('change', '.field-type-select', function () {
                const type = $(this).val();
                const optionsContainer = $(this).closest('.field-row').find('.options-container');
                if (['select', 'radio', 'checkbox'].includes(type)) {
                    optionsContainer.removeClass('d-none');
                } else {
                    optionsContainer.addClass('d-none');
                }
            });

            $(document).on('click', '.remove-btn', function () {
                $(this).closest('.field-row').remove();
            });

            // Attendance Only checkbox logic
            $('#disable_registration').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#enable_qr').prop('checked', false).prop('disabled', true);
                } else {
                    $('#enable_qr').prop('disabled', false);
                }
            });

            // Add one field by default if empty
            // addField(); 
        });
    </script>

    <style>
        .remove-btn,
        .remove-date-btn {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            line-height: 15px;
            padding: 0;
            z-index: 10;
        }

        .field-row:hover,
        .date-row:hover {
            border-color: #01a9ac !important;
        }
    </style>
@endpush