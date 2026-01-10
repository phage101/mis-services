<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Your Request - MIS Services</title>
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

        .btn-outline-navy {
            color: #002147;
            border-color: #002147;
            background: transparent;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-outline-navy:hover {
            background: #002147;
            color: white;
        }

        .ticket-item {
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .ticket-item:hover {
            border-color: #002147;
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

        .bg-ongoing {
            background-color: #7460ee;
            color: #fff;
        }

        .bg-completed {
            background-color: #2962ff;
            color: #fff;
        }

        .bg-cancelled {
            background-color: #f62d51;
            color: #fff;
        }

        .response-box {
            background: #f8f9fa;
            border-left: 4px solid #002147;
            padding: 20px;
            margin-top: 20px;
            border-radius: 0 4px 4px 0;
            font-style: italic;
        }

        /* Modal Styling */
        .modal-header {
            background: #002147;
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
                    <h2>Track Service Desk Request</h2>
                    <p class="mb-0 text-white-50">Check your ticket status and latest updates</p>
                </div>

                <div class="form-body">
                    <form action="{{ route('public.tickets.track') }}" method="GET" class="mb-5">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-lg"
                                placeholder="Email Address or Request Number (e.g. ICT-2026-01-001)"
                                value="{{ $search }}" required>
                            <div class="input-group-append">
                                <button class="btn btn-navy" type="submit">
                                    <i class="mdi mdi-magnify mr-2"></i> Track Now
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($search)
                        <h5 class="section-title">Search Results</h5>
                        @if($tickets && $tickets->count() > 0)
                            @foreach($tickets as $ticket)
                                <div class="ticket-item" data-toggle="modal" data-target="#ticketModal{{ $ticket->id }}">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="font-weight-bold mb-1 text-navy">{{ $ticket->request_number }}</h6>
                                            <p class="mb-0 text-muted small"><i class="mdi mdi-calendar"></i>
                                                {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                        <span class="status-badge bg-{{ $ticket->status }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge badge-light border">{{ $ticket->requestType->name }}</span>
                                        <span class="badge badge-light border">{{ $ticket->category->name }}</span>
                                    </div>

                                    <p class="mt-2 mb-0"><strong>Issue:</strong> {{ Str::limit($ticket->complaint, 150) }}
                                    </p>

                                    @if($ticket->responses->count() > 0)
                                        <div class="response-box">
                                            <p class="mb-1 small font-weight-bold text-navy">Latest Update
                                                ({{ $ticket->responses->first()->created_at->diffForHumans() }}):</p>
                                            <p class="mb-0">{{ $ticket->responses->first()->action_taken }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Modal for this ticket -->
                                <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title text-white">
                                                    <i class="mdi mdi-ticket-confirmation mr-2"></i>
                                                    Request Details: {{ $ticket->request_number }}
                                                </h5>
                                                <button type="button" class="close text-white outline-none" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="row mb-4">
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="detail-label">Requestor Name</div>
                                                        <div class="detail-value text-primary">{{ $ticket->requestor->name }}
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-3 text-sm-right">
                                                        <div class="detail-label">Status</div>
                                                        <span class="status-badge bg-{{ $ticket->status }}">
                                                            {{ ucfirst($ticket->status) }}
                                                        </span>
                                                    </div>
                                                    <div class="col-sm-4 mb-3">
                                                        <div class="detail-label">Urgency</div>
                                                        <div class="detail-value">
                                                            @php
                                                                $urgencyColors = [
                                                                    'low' => 'text-success',
                                                                    'medium' => 'text-warning',
                                                                    'high' => 'text-danger',
                                                                    'critical' => 'text-danger font-weight-bold'
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="{{ $urgencyColors[$ticket->urgency] ?? '' }}">{{ ucfirst($ticket->urgency) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 mb-3">
                                                        <div class="detail-label">Request Type</div>
                                                        <div class="detail-value">{{ $ticket->requestType->name }}</div>
                                                    </div>
                                                    <div class="col-sm-4 mb-3">
                                                        <div class="detail-label">Category</div>
                                                        <div class="detail-value">{{ $ticket->category->name }}</div>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <div class="detail-label">Complaint / Issue</div>
                                                    <div class="p-3 bg-light rounded text-dark">
                                                        <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $ticket->complaint }}</p>
                                                    </div>
                                                </div>

                                                @if($ticket->responses->count() > 0)
                                                    <div class="mt-4">
                                                        <h6 class="font-weight-bold mb-3"><i class="mdi mdi-history mr-2"></i> Action
                                                            History</h6>
                                                        @foreach($ticket->responses as $response)
                                                            <div class="response-box mb-3 border-left-success bg-light">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span
                                                                        class="small font-weight-bold text-navy">{{ $response->user->name }}</span>
                                                                    <span
                                                                        class="small text-muted">{{ $response->created_at->format('M d, Y h:i A') }}</span>
                                                                </div>
                                                                <p class="mb-0 text-dark">{{ $response->action_taken }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="mdi mdi-file-search-outline text-muted" style="font-size: 64px;"></i>
                                <p class="mt-3 text-muted">No tickets found for "<strong>{{ $search }}</strong>".<br>Please
                                    check your request number or email address.</p>
                            </div>
                        @endif
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('home') }}" class="text-muted"><i class="mdi mdi-arrow-left"></i> Back to
                            Portal</a>
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
            @if($search && $tickets && $tickets->count() === 1)
                $('#ticketModal{{ $tickets->first()->id }}').modal('show');
            @endif
        });
    </script>
</body>

</html>