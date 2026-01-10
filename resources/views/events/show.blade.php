@extends('layouts.nice-admin')

@section('title', 'Event Details')

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
    <style>
        .sticky-filters {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #fff;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 2px solid #f1f1f1;
        }

        #participants-table_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }

        #participants-table thead th {
            background-color: #f8f9fa;
        }

        .event-banner {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h3 class="font-weight-bold">{{ $event->title }}</h3>
                            <span class="badge badge-lg {{ [
        'upcoming' => 'badge-primary',
        'ongoing' => 'badge-info',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger'
    ][$event->status] ?? 'badge-secondary' }}">{{ ucfirst($event->status) }}</span>
                        </div>
                        <div>
                            <a href="{{ route('events.index') }}" class="btn btn-secondary mr-2">
                                <i class="mdi mdi-arrow-left"></i> Back
                            </a>
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">
                                <i class="mdi mdi-pencil"></i> Edit Event
                            </a>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card border shadow-sm mb-0">
                                <div class="card-body p-0">
                                    @if($event->banner_image)
                                        <img src="{{ asset('storage/' . $event->banner_image) }}" alt="Event Banner" class="event-banner">
                                    @endif
                                    
                                    <div class="p-3">
                                        <h5 class="font-weight-bold mb-3 border-bottom pb-2">Event Information</h5>

                                    <div class="mb-4">
                                        <h6 class="font-weight-bold text-muted small text-uppercase">Description</h6>
                                        <p class="mb-0">{{ $event->description ?: 'No description provided.' }}</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <h6 class="font-weight-bold text-muted small text-uppercase">Event Type & Classification</h6>
                                            <p class="mb-0">
                                                <i class="mdi mdi-tag mr-1 text-info"></i> {{ $event->event_type }} 
                                                <span class="badge badge-info ml-1">{{ $event->classification }}</span>
                                                @if($event->enable_qr)
                                                    <span class="badge badge-success ml-1"><i class="mdi mdi-qrcode"></i> QR Enabled</span>
                                                @else
                                                    <span class="badge badge-secondary ml-1"><i class="mdi mdi-qrcode-off"></i> QR Disabled</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <h6 class="font-weight-bold text-muted small text-uppercase">Venue
                                                ({{ ucfirst($event->venue_type) }})</h6>
                                            <p class="mb-0 font-weight-bold text-info"><i
                                                    class="mdi mdi-map-marker mr-1"></i> {{ $event->venue_platform }}</p>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <h6 class="font-weight-bold text-muted small text-uppercase">Event Schedule</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mt-2 mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($event->dates as $eventDate)
                                                        <tr>
                                                            <td>{{ $eventDate->date->format('F d, Y') }} ({{ $eventDate->date->format('l') }})</td>
                                                            <td>{{ \Carbon\Carbon::parse($eventDate->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($eventDate->end_time)->format('h:i A') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                            <div class="card border shadow-sm mb-0 text-center h-100">
                                <div class="card-body">
                                    <h5 class="font-weight-bold mb-3">Registration Link</h5>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="regLink"
                                            value="{{ route('events.register', $event) }}" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-info" type="button" onclick="copyRegLink()">
                                                <i class="mdi mdi-content-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="qr-container p-3 border rounded bg-white d-inline-block mb-3">
                                        <img id="qrCodeImg"
                                            src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('events.register', $event)) }}"
                                            alt="QR Code" style="width: 150px; height: 150px;">
                                    </div>

                                    <div class="mt-2">
                                        <button class="btn btn-outline-info btn-sm" onclick="downloadQR()">
                                            <i class="mdi mdi-download"></i> Download QR Code
                                        </button>
                                    </div>

                                    <p class="text-muted small mt-3 mb-0">Share this QR code or link with participants.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Section -->
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#registration-form" role="tab">
                                <span class="hidden-sm-up"><i class="mdi mdi-format-list-bulleted"></i></span>
                                <span class="hidden-xs-down">Registration Form
                                    ({{ $event->formFields->count() }} Custom Fields)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#participants" role="tab">
                                <span><i class="mdi mdi-account-group mr-1"></i> Registered List ({{ $event->participants->count() }})</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#checkin-station" role="tab" id="scanner-tab">
                                <span><i class="mdi mdi-qrcode-scan mr-1"></i> Check-in Station & Attendance</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Registration Form Preview Tab -->
                        <div class="tab-pane active" id="registration-form" role="tabpanel">
                            <div class="card bg-light border p-4">
                                <h5 class="font-weight-bold border-bottom pb-2 mb-3">Form Preview</h5>

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
                                    $enabledFields = $event->registration_fields ?? [];
                                @endphp

                                @foreach($standardFields as $key => $label)
                                    @if(in_array($key, $enabledFields))
                                        <div class="mb-3">
                                            <label class="font-weight-bold">{{ $label }}</label>
                                            @if($key == 'province')
                                                <select class="form-control" disabled>
                                                    <option>Select province</option>
                                                    <option>Aklan</option>
                                                    <option>Antique</option>
                                                    <option>Capiz</option>
                                                    <option>Guimaras</option>
                                                    <option>Iloilo</option>
                                                    <option>Negros Occidental</option>
                                                    <option>Others</option>
                                                </select>
                                            @elseif($key == 'sex')
                                                <select class="form-control" disabled>
                                                    <option>Select sex</option>
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            @elseif($key == 'age_bracket')
                                                <select class="form-control" disabled>
                                                    <option>Select age bracket</option>
                                                    @foreach(['Below 18', '18-24', '25-34', '35-44', '45-54', '55-64', '65 and above'] as $age)
                                                        <option>{{ $age }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" class="form-control" disabled
                                                    placeholder="Participant's {{ $label }}">
                                            @endif
                                        </div>
                                    @endif
                                @endforeach

                                @foreach($event->formFields as $field)
                                    <div class="mb-3">
                                        <label class="font-weight-bold">{{ $field->label }}
                                            {!! $field->is_required ? '<span class="text-danger">*</span>' : '' !!}</label>

                                        @if($field->field_type == 'text')
                                            <input type="text" class="form-control" disabled>
                                        @elseif($field->field_type == 'textarea')
                                            <textarea class="form-control" rows="2" disabled></textarea>
                                        @elseif($field->field_type == 'select')
                                            <select class="form-control" disabled>
                                                @foreach($field->options as $option)
                                                    <option>{{ trim($option) }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($field->field_type == 'radio')
                                            <div class="mt-1">
                                                @foreach($field->options as $option)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" disabled>
                                                        <label class="form-check-label">{{ trim($option) }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->field_type == 'checkbox')
                                            <div class="mt-1">
                                                @foreach($field->options as $option)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" disabled>
                                                        <label class="form-check-label">{{ trim($option) }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                @if($event->formFields->isEmpty())
                                    <div class="text-center py-3 text-muted">
                                        <i class="mdi mdi-alert-circle-outline mdi-24px"></i>
                                        <p>No custom fields added for this event.</p>
                                    </div>
                                @endif

                                <div class="text-center mt-3 border-top pt-3">
                                    <a href="{{ route('events.register', $event) }}" target="_blank"
                                        class="btn btn-outline-info">
                                        <i class="mdi mdi-eye"></i> View Actual Registration Page
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Registered List Tab -->
                        <div class="tab-pane" id="participants" role="tabpanel">
                            <div class="sticky-filters shadow-sm p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 font-weight-bold text-dark"><i class="mdi mdi-filter-variant mr-1"></i>
                                        Filters</h5>
                                    <div>
                                        <button class="btn btn-sm btn-info mr-2" id="refresh-list">
                                            <i class="mdi mdi-refresh"></i> Refresh List
                                        </button>
                                        <a href="{{ route('events.print-attendance', $event) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-printer"></i> Print Attendance Sheet
                                        </a>
                                    </div>
                                </div>

                                <div class="row">
                                    @php
                                        $filterFields = ['organization', 'sex', 'province'];
                                    @endphp
                                    @foreach($standardFields as $key => $label)
                                        @if(in_array($key, $enabledFields) && in_array($key, $filterFields))
                                            <div class="col-md-3 mb-2">
                                                <label class="small font-weight-bold">By {{ $label }}</label>
                                                <select class="form-control form-control-sm filter-input"
                                                    data-column-class="col-{{ $key }}">
                                                    <option value="">All {{ $label }}</option>
                                                    @foreach($event->participants->pluck($key)->unique()->filter()->sort() as $value)
                                                        <option value="{{ $value }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    {{-- Custom Fields Filters --}}
                                    @foreach($event->formFields as $field)
                                        @if(in_array($field->field_type, ['select', 'radio']))
                                            <div class="col-md-3 mb-2">
                                                <label class="small font-weight-bold">By {{ $field->label }}</label>
                                                <select class="form-control form-control-sm filter-input"
                                                    data-column-class="col-field-{{ $field->id }}">
                                                    <option value="">All {{ $field->label }}</option>
                                                    @foreach($field->options as $option)
                                                        <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="participants-table-container">
                                @include('events.partials.participants_table')
                            </div>
                        </div>

                        <!-- Check-in Station Tab -->
                        <div class="tab-pane" id="checkin-station" role="tabpanel">
                            <div class="row">
                                <!-- Left Column: Scanner & Controls -->
                                <div class="col-lg-4">
                                    <div class="card border shadow-sm sticky-top" style="top: 20px;">
                                        <div class="card-body">
                                            <h5 class="font-weight-bold mb-3"><i class="mdi mdi-qrcode-scan mr-1 text-primary"></i> Scan Station</h5>
                                            
                                            <!-- Date Selector for QR Scanner -->
                                            <div class="form-group mb-4 bg-light p-3 rounded border">
                                                <label class="font-weight-bold small text-uppercase">Scanning for Session:</label>
                                                <select id="scanner-date-id" class="form-control font-weight-bold border-primary">
                                                    @foreach($event->dates as $date)
                                                        <option value="{{ $date->id }}" {{ $date->date->isToday() ? 'selected' : '' }}>
                                                            Day {{ $loop->iteration }}: {{ $date->date->format('F d, Y') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted mt-2 d-block"><i class="mdi mdi-information-outline"></i> The attendance list on the right will sync with this selection.</small>
                                            </div>

                                            <div id="reader" style="width: 100%; min-height: 250px; border: 2px dashed #ddd; border-radius: 12px; overflow: hidden; background: #fafafa; margin: 0 auto;"></div>
                                            
                                            <div class="mt-3">
                                                <button class="btn btn-primary btn-block shadow-sm" id="start-scanner">
                                                    <i class="mdi mdi-play mr-1"></i> Start Scanning
                                                </button>
                                                <button class="btn btn-danger btn-block d-none shadow-sm" id="stop-scanner">
                                                    <i class="mdi mdi-stop mr-1"></i> Stop Camera
                                                </button>
                                            </div>

                                            <hr class="my-4">

                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="font-weight-bold mb-0 text-muted small text-uppercase">Recent Activity</h6>
                                                <button class="btn btn-link btn-xs p-0 text-danger" onclick="document.getElementById('scanner-results').innerHTML = ''">Clear</button>
                                            </div>
                                            <div id="scanner-results" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                                <div class="text-center text-muted py-4">
                                                    <p class="small font-italic mb-0">No scans yet in this session.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Attendance List -->
                                <div class="col-lg-8">
                                    <div class="card border shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="card-title mb-0">Attendance Tracker</h5>
                                                <div class="btn-group">
                                                    <a href="{{ route('events.print-attendance', $event) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="mdi mdi-printer"></i> Print Sheet
                                                    </a>
                                                    <button class="btn btn-sm btn-info" onclick="location.reload()">
                                                        <i class="mdi mdi-refresh"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Date Pills (Synced with Scanner Select) -->
                                            <ul class="nav nav-pills mb-4" id="attendance-pills" role="tablist">
                                                @foreach($event->dates as $index => $date)
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                                           id="pill-day-{{ $date->id }}-tab"
                                                           data-toggle="pill" 
                                                           href="#attendance-day-{{ $date->id }}" 
                                                           data-date-id="{{ $date->id }}"
                                                           role="tab">
                                                            Day {{ $index + 1 }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <div class="tab-content">
                                                @foreach($event->dates as $date)
                                                    <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="attendance-day-{{ $date->id }}" role="tabpanel">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered datatable-attendance" id="attendance-table-{{ $date->id }}">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th>Participant</th>
                                                                        <th>Organization</th>
                                                                        <th>Status</th>
                                                                        <th>Time</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($event->participants as $participant)
                                                                        @php
                                                                            $att = $participant->attendances->where('event_date_id', $date->id)->first();
                                                                        @endphp
                                                                        <tr id="row-{{ $date->id }}-{{ $participant->uuid }}">
                                                                            <td>
                                                                                <span class="font-weight-bold">{{ $participant->name }}</span>
                                                                            </td>
                                                                            <td><small>{{ Str::limit($participant->organization, 20) }}</small></td>
                                                                            <td class="status-cell">
                                                                                @if($att)
                                                                                    <span class="badge badge-success">Present</span>
                                                                                @else
                                                                                    <span class="badge badge-secondary">Not Scanned</span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="time-cell small">{{ $att ? $att->scanned_at->format('h:i A') : '-' }}</td>
                                                                            <td>
                                                                                @if(!$att)
                                                                                    <button class="btn btn-xs btn-outline-info mark-manual" 
                                                                                        data-participant-id="{{ $participant->uuid }}"
                                                                                        data-date-id="{{ $date->id }}">
                                                                                        Mark
                                                                                    </button>
                                                                                @else
                                                                                    <span class="text-success"><i class="mdi mdi-check-circle"></i></span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function copyRegLink() {
            var copyText = document.getElementById("regLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            alert("Registration link copied to clipboard!");
        }

        async function downloadQR() {
            const img = document.getElementById('qrCodeImg');
            const url = img.src;
            const fileName = 'Registration_QR_{{ Str::slug($event->title) }}.png';

            try {
                const response = await fetch(url);
                const blob = await response.blob();
                const blobUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = blobUrl;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(blobUrl);
            } catch (error) {
                console.error('Download failed:', error);
                // Fallback: Open in new window
                window.open(url, '_blank');
            }
        }

        $(document).ready(function () {
            let table;
            let attendanceTables = {};

            function initializeDataTable() {
                if (!$('#participants-table').length) return;
                
                table = $('#participants-table').DataTable({
                    "destroy": true,
                    "order": [[0, "asc"]],
                    "pageLength": 25,
                    "scrollX": true,
                    "autoWidth": false,
                    "language": {
                        "search": "Filter Registered:"
                    },
                    "columnDefs": [
                        { "width": "200px", "targets": 0 }
                    ]
                });
            }

            function initializeAttendanceTables() {
                $('.datatable-attendance').each(function() {
                    let id = $(this).attr('id');
                    attendanceTables[id] = $(this).DataTable({
                        "pageLength": 10,
                        "order": [[2, "desc"]], // Show 'Present' first or recently marked
                        "language": {
                            "emptyTable": "No participants registered yet."
                        }
                    });
                });
            }

            initializeDataTable();
            initializeAttendanceTables();

            // Custom Filtering for Registered List
            $('.filter-input').on('change', function () {
                let columnClass = $(this).data('column-class');
                let columnIndex = $(this).data('column-index');
                let val = $(this).val();

                if (columnClass) {
                    let colIndex = table.column('.' + columnClass).index();
                    if (colIndex !== undefined) {
                        table.column(colIndex).search(val ? '^' + val + '$' : '', true, false).draw();
                    }
                } else if (columnIndex !== undefined) {
                    table.column(columnIndex).search(val ? '^' + val + '$' : '', true, false).draw();
                }
            });

            $('#refresh-list').on('click', function() {
                location.reload(); // Simplest way to refresh all counts and relationships
            });

            // Adjust table columns when switching tabs
            $('a[data-toggle="tab"], a[data-toggle="pill"]').on('shown.bs.tab shown.bs.pill', function (e) {
                if (table) table.columns.adjust();
                $.each(attendanceTables, function(id, tableInstance) {
                    tableInstance.columns.adjust();
                });
            });

            // Sync Scanner Session Dropdown with Attendance Day Pills
            $('#scanner-date-id').on('change', function() {
                let dateId = $(this).val();
                $(`#pill-day-${dateId}-tab`).tab('show');
            });

            $('#attendance-pills a').on('shown.bs.tab', function(e) {
                let dateId = $(e.target).data('date-id');
                $('#scanner-date-id').val(dateId);
                // Adjust DataTables columns
                if (attendanceTables[`attendance-table-${dateId}`]) {
                    attendanceTables[`attendance-table-${dateId}`].columns.adjust();
                }
            });

            // Re-adjust columns when Check-in Station tab is shown
            $('a[href="#checkin-station"]').on('shown.bs.tab', function() {
                let activeDateId = $('#scanner-date-id').val();
                if (attendanceTables[`attendance-table-${activeDateId}`]) {
                    attendanceTables[`attendance-table-${activeDateId}`].columns.adjust();
                }
            });

            function updateAttendanceRow(dateId, participantUuid, timeStr) {
                let row = $(`#row-${dateId}-${participantUuid}`);
                if (row.length) {
                    row.find('.status-cell').html('<span class="badge badge-success animated pulse infinite">Present</span>');
                    row.find('.time-cell').text(timeStr || new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
                    row.find('td:last-child').html('<span class="text-success"><i class="mdi mdi-check-circle"></i></span>');
                    row.addClass('table-success animated flash');
                    setTimeout(() => {
                        row.removeClass('table-success animated flash');
                        row.find('.status-cell .badge').removeClass('animated pulse infinite');
                    }, 3000);
                }
            }

            // Manual Mark Attendance
            $(document).on('click', '.mark-manual', function() {
                let btn = $(this);
                let participantUuid = btn.data('participant-id');
                let dateId = btn.data('date-id');
                
                btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');

                $.ajax({
                    url: `/events/{{ $event->uuid }}/mark-attendance/${participantUuid}`,
                    type: 'GET',
                    data: { event_date_id: dateId },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        if (response.status === 'success' || response.status === 'info') {
                            updateAttendanceRow(dateId, participantUuid);
                        } else {
                            btn.prop('disabled', false).text('Mark');
                            alert(response.message);
                        }
                    },
                    error: function() {
                        btn.prop('disabled', false).text('Mark');
                        alert('Failed to mark attendance.');
                    }
                });
            });

            // QR Scanner Implementation
            let html5QrCode = null;
            const scannerContainer = document.getElementById('reader');
            const startBtn = document.getElementById('start-scanner');
            const stopBtn = document.getElementById('stop-scanner');
            const resultsLog = document.getElementById('scanner-results');

            function onScanSuccess(decodedText, decodedResult) {
                if (!decodedText.includes('mark-attendance')) return;

                stopScanner();
                
                try {
                    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3');
                    audio.play().catch(e => {});
                } catch(e) {}

                // Get selected date from dropdown
                let selectedDateId = $('#scanner-date-id').val();

                $.ajax({
                    url: decodedText,
                    type: 'GET',
                    data: { event_date_id: selectedDateId },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(response) {
                        logResult(response.status, response.message, response.participant);
                        if (response.status === 'success' || response.status === 'info') {
                            updateAttendanceRow(selectedDateId, response.participant.uuid);
                        }
                    },
                    error: function(xhr) {
                        let msg = 'Error processing QR code.';
                        if (xhr.status === 400) {
                            let resp = JSON.parse(xhr.responseText);
                            msg = resp.message;
                        }
                        logResult('danger', msg);
                    },
                    complete: function() {
                        setTimeout(startScanner, 2000);
                    }
                });
            }

            function logResult(status, message, participant = null) {
                const badgeClass = status === 'success' ? 'badge-success' : (status === 'info' ? 'badge-info' : 'badge-danger');
                const icon = status === 'success' ? 'mdi-check-circle' : (status === 'info' ? 'mdi-information' : 'mdi-alert-circle');
                
                if (resultsLog.querySelector('.text-muted')) {
                    resultsLog.innerHTML = '';
                }

                const resultHtml = `
                    <div class="alert alert-light border shadow-sm mb-2 p-3 fade show animated bounceInDown">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <i class="mdi ${icon} text-${status === 'danger' ? 'danger' : (status === 'success' ? 'success' : 'info')}" style="font-size: 24px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 font-weight-bold">${message}</h6>
                                <p class="mb-0 small text-muted">${new Date().toLocaleTimeString()}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="badge ${badgeClass}">${status.toUpperCase()}</span>
                            </div>
                        </div>
                    </div>
                `;
                resultsLog.insertAdjacentHTML('afterbegin', resultHtml);
            }

            function startScanner() {
                if (html5QrCode === null) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
                    .then(() => {
                        startBtn.classList.add('d-none');
                        stopBtn.classList.remove('d-none');
                    })
                    .catch(err => {
                        console.error("Scanner error:", err);
                        alert("Could not start camera. Please ensure you have given permission.");
                    });
            }

            function stopScanner() {
                if (html5QrCode && html5QrCode.isScanning) {
                    html5QrCode.stop().then(() => {
                        startBtn.classList.remove('d-none');
                        stopBtn.classList.add('d-none');
                    }).catch(err => console.error("Stop error:", err));
                }
            }

            startBtn.addEventListener('click', startScanner);
            stopBtn.addEventListener('click', stopScanner);

            // Stop scanner when switching tabs
            $('a[data-toggle="tab"]').on('hide.bs.tab', function (e) {
                stopScanner();
            });
        });
    </script>
@endpush