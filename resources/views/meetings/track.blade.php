<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Meeting Request - MIS Services</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    
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
            max-width: 850px;
            overflow: hidden;
        }

        .form-header {
            background: #28a745;
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

        .meeting-item {
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .meeting-item:hover {
            border-color: #28a745;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            background: #fdfdfd;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .bg-pending {
            background-color: #ffbc34;
            color: #fff;
        }

        .bg-scheduled {
            background-color: #28a745;
            color: #fff;
        }

        .bg-conflict {
            background-color: #7460ee;
            color: #fff;
        }

        .bg-cancelled {
            background-color: #f62d51;
            color: #fff;
        }

        .details-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin-top: 20px;
            border-radius: 0 4px 4px 0;
        }

        .slot-pill {
            display: inline-block;
            background: #eef1f5;
            padding: 4px 12px;
            border-radius: 4px;
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 12px;
            color: #4f5467;
        }

        /* Modal Styling */
        .modal-header {
            background: #28a745;
            color: white;
        }

        .modal-title {
            font-weight: 700;
        }

        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #adb5bd;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .detail-value {
            font-weight: 600;
            color: #3e5569;
            margin-bottom: 15px;
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
                    <h2>Track Meeting Request</h2>
                    <p class="mb-0 text-white-50">Check your meeting schedule and connection details</p>
                </div>

                <div class="form-body">
                    <form action="{{ route('public.meetings.track') }}" method="GET" class="mb-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg" 
                                placeholder="Email Address or Meeting ID (e.g. MTG-2026-01-001)" 
                                value="{{ $search }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-success-green" type="submit">
                                    <i class="mdi mdi-calendar-search mr-2"></i> Track Now
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($search)
                        <h5 class="section-title">Search Results</h5>
                        @if($meetings && $meetings->count() > 0)
                            @foreach($meetings as $meeting)
                                <div class="meeting-item" data-toggle="modal" data-target="#meetingModal{{ $meeting->id }}">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="font-weight-bold mb-1 text-success">{{ $meeting->request_number }}
                                            </h6>
                                            <p class="mb-0 text-muted small"><i class="mdi mdi-timetable"></i> Requested
                                                on {{ $meeting->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <span class="status-badge bg-{{ $meeting->status }}">
                                            {{ ucfirst($meeting->status) }}
                                        </span>
                                    </div>

                                    <h5 class="mb-2 font-weight-bold">{{ $meeting->topic }}</h5>
                                    <p class="text-muted small mb-3">{{ $meeting->description ?? 'No agenda provided.' }}
                                    </p>

                                    <div class="mb-3">
                                        <p class="small font-weight-bold mb-2">Proposed/Scheduled Times:</p>
                                        @foreach($meeting->slots as $slot)
                                            <div class="slot-pill">
                                                <i class="mdi mdi-calendar"></i> {{ $slot->meeting_date->format('M d') }} |
                                                <i class="mdi mdi-clock"></i>
                                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} -
                                                {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                                @if($slot->is_approved) <i
                                                class="mdi mdi-check-circle text-success ml-1"></i> @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($meeting->status === 'scheduled')
                                        <div class="details-box">
                                            <h6 class="font-weight-bold text-success mb-3"><i
                                                    class="mdi mdi-information-outline"></i> Connection Details</h6>
                                            <p class="mb-0 small text-muted">Click to see full meeting information and links.</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Modal for this meeting -->
                                <div class="modal fade" id="meetingModal{{ $meeting->id }}" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title text-white">
                                                    <i class="mdi mdi-calendar-clock mr-2"></i>
                                                    Meeting Request: {{ $meeting->request_number }}
                                                </h5>
                                                <button type="button" class="close text-white outline-none"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row mb-4">
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="detail-label">Requestor Name</div>
                                                        <div class="detail-value text-success">
                                                            {{ $meeting->requestor->name }}</div>
                                                    </div>
                                                    <div class="col-sm-6 mb-3 text-sm-right">
                                                        <div class="detail-label">Status</div>
                                                        <span class="status-badge bg-{{ $meeting->status }}">
                                                            {{ ucfirst($meeting->status) }}
                                                        </span>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="detail-label">Topic / Agenda</div>
                                                        <h5 class="font-weight-bold">{{ $meeting->topic }}</h5>
                                                        <div class="p-3 bg-light rounded text-dark mt-2">
                                                            <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $meeting->description ?? 'No agenda provided.' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <div class="detail-label">Proposed/Scheduled Times</div>
                                                    <div class="row mt-2">
                                                        @foreach($meeting->slots as $slot)
                                                            <div class="col-md-6 mb-2">
                                                                <div
                                                                    class="p-2 border rounded d-flex align-items-center {{ $slot->is_approved ? 'border-success bg-light' : '' }}">
                                                                    <i
                                                                        class="mdi mdi-clock-outline mr-2 {{ $slot->is_approved ? 'text-success' : 'text-muted' }}"></i>
                                                                    <div>
                                                                        <div class="small font-weight-bold">
                                                                            {{ $slot->meeting_date->format('l, M d, Y') }}
                                                                        </div>
                                                                        <div class="small text-muted">
                                                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                                                        </div>
                                                                    </div>
                                                                    @if($slot->is_approved)
                                                                        <div class="ml-auto text-success small font-weight-bold">
                                                                            <i class="mdi mdi-check-circle"></i> SELECTED
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                @if($meeting->status === 'scheduled')
                                                    <div class="details-box">
                                                        <h6 class="font-weight-bold text-success mb-3"><i
                                                                class="mdi mdi-link-variant mr-2"></i> Final Arrangement
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-sm-6 mb-3">
                                                                <div class="detail-label">Hosting Platform</div>
                                                                <div class="detail-value">
                                                                    {{ $meeting->platform->name ?? 'TBA' }}</div>
                                                            </div>
                                                            <div class="col-sm-6 mb-3">
                                                                <div class="detail-label">Technical Host</div>
                                                                <div class="detail-value">
                                                                    {{ $meeting->host->name ?? 'TBA' }}</div>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="detail-label">Meeting Link / Access Details
                                                                </div>
                                                                @if($meeting->meeting_details)
                                                                    <div class="p-3 border rounded bg-white mt-1 shadow-sm">
                                                                        <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $meeting->meeting_details }}</p>
                                                                    </div>
                                                                @else
                                                                    <em class="text-muted">Link is being prepared...</em>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-outline-success"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="mdi mdi-calendar-question text-muted" style="font-size: 64px;"></i>
                                <p class="mt-3 text-muted">No meeting requests found for "<strong>{{ $search }}</strong>".<br>Please check your request number or email address.</p>
                            </div>
                        @endif
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('home') }}" class="text-muted"><i class="mdi mdi-arrow-left"></i> Back to Portal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".preloader").fadeOut();
            @if($search && $meetings && $meetings->count() === 1)
                $('#meetingModal{{ $meetings->first()->id }}').modal('show');
            @endif
        });
    </script>
</body>

</html>
