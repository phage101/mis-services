@extends('layouts.nice-admin')

@section('title', 'Edit Event')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Edit Event: {{ $event->title }}</h4>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Details
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

                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title">Event Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title', $event->title) }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description">Description (Optional)</label>
                                    <textarea name="description" id="description" class="form-control"
                                        rows="3">{{ old('description', $event->description) }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="banner_image">Social Media Card / Banner (Optional)</label>

                                    @if($event->banner_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Current Banner"
                                                class="img-fluid rounded border shadow-sm" style="max-height: 150px;">
                                            <p class="small text-muted mt-1">Current Banner</p>
                                        </div>
                                    @endif

                                    <div class="custom-file">
                                        <input type="file" name="banner_image" id="banner_image" class="custom-file-input">
                                        <label class="custom-file-label" for="banner_image">
                                            {{ $event->banner_image ? 'Change image...' : 'Choose image...' }}
                                        </label>
                                    </div>
                                    <small class="text-muted">Recommended size: 1200x630 (aspect ratio 1.91:1)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="status">Event Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="upcoming" {{ $event->status == 'upcoming' ? 'selected' : '' }}>Upcoming
                                        </option>
                                        <option value="ongoing" {{ $event->status == 'ongoing' ? 'selected' : '' }}>Ongoing
                                        </option>
                                        <option value="completed" {{ $event->status == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ $event->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="event_type">Category</label>
                                    <select name="event_type" id="event_type" class="form-control">
                                        @foreach(['Meeting', 'Workshop', 'Seminar', 'Training', 'Other'] as $type)
                                            <option value="{{ $type }}" {{ $event->event_type == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="classification">Classification</label>
                                    <select name="classification" id="classification" class="form-control">
                                        <option value="Internal" {{ $event->classification == 'Internal' ? 'selected' : '' }}>
                                            Internal</option>
                                        <option value="External" {{ $event->classification == 'External' ? 'selected' : '' }}>
                                            External</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="expected_participants">Expected Participants (Optional)</label>
                                    <input type="number" name="expected_participants" id="expected_participants"
                                        class="form-control"
                                        value="{{ old('expected_participants', $event->expected_participants) }}">
                                </div>
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="enable_qr" name="enable_qr"
                                            value="1" {{ $event->enable_qr ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold" for="enable_qr">
                                            <i class="mdi mdi-qrcode mr-1"></i> Enable QR Code & Email Sending
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1 pl-4">Sends confirmation email with QR code upon
                                        registration.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="venue_type">Venue Type</label>
                                    <select name="venue_type" id="venue_type" class="form-control">
                                        <option value="onsite" {{ $event->venue_type == 'onsite' ? 'selected' : '' }}>Onsite
                                        </option>
                                        <option value="online" {{ $event->venue_type == 'online' ? 'selected' : '' }}>Online
                                        </option>
                                        <option value="hybrid" {{ $event->venue_type == 'hybrid' ? 'selected' : '' }}>Hybrid
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="venue_platform">Venue Name / Platform Link</label>
                                    <input type="text" name="venue_platform" id="venue_platform" class="form-control"
                                        value="{{ old('venue_platform', $event->venue_platform) }}" required>
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
                                    @foreach($event->dates as $index => $eventDate)
                                        <div class="date-row mb-3 p-3 bg-light rounded border position-relative">
                                            @if(!$loop->first)
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute remove-date-btn"
                                                    style="top:-10px; right:-10px;">&times;</button>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group mb-0">
                                                        <label class="small font-weight-bold">Date</label>
                                                        <input type="date" name="dates[{{ $index }}][date]" class="form-control"
                                                            value="{{ $eventDate->date->format('Y-m-d') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-0">
                                                        <label class="small font-weight-bold">Start Time</label>
                                                        <input type="time" name="dates[{{ $index }}][start_time]"
                                                            class="form-control"
                                                            value="{{ \Carbon\Carbon::parse($eventDate->start_time)->format('H:i') }}"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-0">
                                                        <label class="small font-weight-bold">End Time</label>
                                                        <input type="time" name="dates[{{ $index }}][end_time]"
                                                            class="form-control"
                                                            value="{{ \Carbon\Carbon::parse($eventDate->end_time)->format('H:i') }}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
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
                                        $enabledFields = $event->registration_fields ?? array_keys($standardFields);
                                    @endphp
                                    @foreach($standardFields as $key => $label)
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="field_{{ $key }}"
                                                    name="registration_fields[]" value="{{ $key }}" {{ in_array($key, $enabledFields) ? 'checked' : '' }}>
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
                                    @foreach($event->formFields as $index => $field)
                                        <div class="field-row mb-3 p-3 bg-light rounded border position-relative">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute remove-btn"
                                                style="top:-10px; right:-10px;">&times;</button>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="small font-weight-bold">Field Label</label>
                                                    <input type="text" name="form_fields[{{ $index }}][label]"
                                                        class="form-control" value="{{ $field->label }}"
                                                        placeholder="e.g. Food Preference" required>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="small font-weight-bold">Field Type</label>
                                                    <select name="form_fields[{{ $index }}][field_type]"
                                                        class="form-control field-type-select">
                                                        <option value="text" {{ $field->field_type == 'text' ? 'selected' : '' }}>
                                                            Short Answer (Text)</option>
                                                        <option value="textarea" {{ $field->field_type == 'textarea' ? 'selected' : '' }}>Long Answer (Paragraph)</option>
                                                        <option value="select" {{ $field->field_type == 'select' ? 'selected' : '' }}>Dropdown</option>
                                                        <option value="radio" {{ $field->field_type == 'radio' ? 'selected' : '' }}>Multiple Choice (Radio)</option>
                                                        <option value="checkbox" {{ $field->field_type == 'checkbox' ? 'selected' : '' }}>Checkboxes</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-2 d-flex align-items-center justify-content-center">
                                                    <div class="form-check pt-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="form_fields[{{ $index }}][is_required]" id="req_{{ $index }}"
                                                            value="1" {{ $field->is_required ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="req_{{ $index }}">Required?</label>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-12 options-container {{ in_array($field->field_type, ['select', 'radio', 'checkbox']) ? '' : 'd-none' }}">
                                                    <label class="small font-weight-bold">Options (Comma separated)</label>
                                                    <input type="text" name="form_fields[{{ $index }}][options]"
                                                        class="form-control"
                                                        value="{{ is_array($field->options) ? implode(',', $field->options) : $field->options }}"
                                                        placeholder="Option 1, Option 2, Option 3">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="alert alert-light border">
                                    <small class="text-muted">
                                        <i class="mdi mdi-information-outline"></i> Standard fields (Name, Type,
                                        Organization) are automatically included. Add custom fields here for additional
                                        information like T-Shirt size, Food preference, etc.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-info btn-lg">
                                <i class="mdi mdi-content-save"></i> Update Event
                            </button>
                        </div>
                    </form>

                    <hr>
                    <form action="{{ route('events.destroy', $event) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this event?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-link text-danger">
                            <i class="mdi mdi-delete"></i> Delete Event Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let fieldIndex = {{ $event->formFields->count() }};
            let dateIndex = {{ $event->dates->count() }};

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