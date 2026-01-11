@extends('layouts.nice-admin')

@section('title', 'CSF Reports')

@section('breadcrumb-title', 'Client Satisfaction Feedback')

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Reports</li>
@endsection

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Date Range Filter -->
    <div class="card mb-3">
        <div class="card-body py-3">
            <form action="{{ route('reports.csf.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-3">
                    <label for="from_date" class="mr-2"><strong>From:</strong></label>
                    <input type="date" class="form-control" id="from_date" name="from_date" 
                        value="{{ request('from_date') }}">
                </div>
                <div class="form-group mr-3">
                    <label for="to_date" class="mr-2"><strong>To:</strong></label>
                    <input type="date" class="form-control" id="to_date" name="to_date" 
                        value="{{ request('to_date') }}">
                </div>
                <button type="submit" class="btn btn-info mr-2">
                    <i class="mdi mdi-filter"></i> Filter
                </button>
                <a href="{{ route('reports.csf.index') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-refresh"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="csfTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab">
                <i class="mdi mdi-view-dashboard"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="feedback-tab" data-toggle="tab" href="#feedback" role="tab">
                <i class="mdi mdi-format-list-bulleted"></i> Feedback List
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3" id="csfTabsContent">
        <!-- Dashboard Tab -->
        <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
            <div class="row">
                <!-- Summary Cards -->
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h4 class="card-title text-white">Total Responses</h4>
                            <div class="d-flex align-items-center">
                                <span class="display-4"><i class="mdi mdi-comment-multiple-outline"></i></span>
                                <div class="ml-auto">
                                    <h2 class="font-weight-medium text-white mb-0">{{ number_format($totalResponses) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h4 class="card-title text-white">Average Overall Rating</h4>
                            <div class="d-flex align-items-center">
                                <span class="display-4"><i class="mdi mdi-star"></i></span>
                                <div class="ml-auto">
                                    <h2 class="font-weight-medium text-white mb-0">{{ number_format($averageRating, 1) }} /
                                        5.0</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h4 class="card-title text-white">Net Satisfaction Rating</h4>
                            <div class="d-flex align-items-center">
                                <span class="display-4"><i class="mdi mdi-thumb-up"></i></span>
                                <div class="ml-auto">
                                    <h2 class="font-weight-medium text-white mb-0">{{ $netSatisfaction }}%</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Criteria Breakdown -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Rating Breakdown by Criteria (Avg)</h4>
                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Criteria</th>
                                            <th class="text-right">Average Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criteriaAverages as $criteria => $avg)
                                            <tr>
                                                <td>{{ $criteria }}</td>
                                                <td class="text-right">
                                                    @if($avg)
                                                        <span class="badge badge-pill badge-info"
                                                            style="font-size: 1rem;">{{ number_format($avg, 1) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Client Type breakdown -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Responses by Client Type</h4>
                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client Type</th>
                                            <th class="text-right">Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($clientTypes as $type => $count)
                                            <tr>
                                                <td>{{ $type ?: 'Not Specified' }}</td>
                                                <td class="text-right">{{ $count }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">No data available</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback List Tab -->
        <div class="tab-pane fade" id="feedback" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Client Feedback List</h4>
                    <div class="table-responsive">
                        <table id="feedback-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Date</th>
                                    <th>Client Type</th>
                                    <th>Overall Rating</th>
                                    <th>Comments</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbacks as $feedback)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tickets.show', $feedback->ticket->uuid) }}">
                                                {{ $feedback->ticket->request_number ?? '#' . $feedback->ticket->id }}
                                            </a>
                                        </td>
                                        <td>{{ $feedback->created_at->format('M d, Y h:i A') }}</td>
                                        <td>{{ $feedback->ticket->requestor->client_type ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $feedback->rating_overall >= 4 ? 'success' : ($feedback->rating_overall >= 3 ? 'warning' : 'danger') }}">
                                                {{ $feedback->rating_overall }} / 5
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($feedback->comments, 50) }}</td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#feedbackModal{{ $feedback->id }}">
                                                <i class="mdi mdi-eye"></i> View
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#signModal{{ $feedback->id }}">
                                                <i class="mdi mdi-pen"></i> E-Sign
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (outside of tabs) -->
    @foreach($feedbacks as $feedback)
        <!-- View Modal -->
        <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Feedback Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Ticket:</strong> {{ $feedback->ticket->request_number }}</p>
                                <p><strong>Client Name:</strong> {{ $feedback->ticket->requestor->name ?? 'N/A' }}</p>
                                <p><strong>Client Type:</strong> {{ $feedback->ticket->requestor->client_type ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $feedback->ticket->requestor->email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date Submitted:</strong> {{ $feedback->created_at->format('F d, Y h:i A') }}</p>
                                <p><strong>Sex:</strong> {{ $feedback->ticket->requestor->sex ?? 'N/A' }}</p>
                                <p><strong>Age Bracket:</strong> {{ $feedback->ticket->requestor->age_bracket ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <hr>
                        <h6><strong>Citizen's Charter Awareness:</strong></h6>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td>CC1 - Awareness of CC</td>
                                <td class="text-right">{{ $feedback->cc1_awareness ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>CC2 - Saw CC Posted</td>
                                <td class="text-right">{{ $feedback->cc2_visibility ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>CC3 - CC Helped in Transaction</td>
                                <td class="text-right">{{ $feedback->cc3_helpfulness ?? 'N/A' }}</td>
                            </tr>
                        </table>

                        <hr>
                        <h6><strong>Service Quality Ratings:</strong></h6>
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Criteria</th>
                                    <th class="text-right">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Responsiveness</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_responsiveness ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Reliability</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_reliability ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Access & Facilities</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_access_facilities ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Communication</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_communication ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Costs</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_costs ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Integrity</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_integrity ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Assurance</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_assurance ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Outcome</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_outcome ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Resource Speaker</td>
                                    <td class="text-right"><span
                                            class="badge badge-info">{{ $feedback->rating_resource_speaker ?? '-' }}</span></td>
                                </tr>
                                <tr class="table-success font-weight-bold">
                                    <td>Overall Rating</td>
                                    <td class="text-right"><span class="badge badge-success"
                                            style="font-size: 1rem;">{{ $feedback->rating_overall }} / 5</span></td>
                                </tr>
                            </tbody>
                        </table>

                        @if($feedback->rating_remarks)
                            <h6><strong>Reasons for Rating:</strong></h6>
                            <p class="alert alert-light">{{ $feedback->rating_remarks }}</p>
                        @endif

                        <hr>
                        <h6><strong>Comments / Suggestions:</strong></h6>
                        <p class="alert alert-light">{{ $feedback->comments ?? 'No comments.' }}</p>

                        <hr>
                        <h6><strong>Signature:</strong></h6>
                        @if($feedback->signature)
                            <img src="{{ $feedback->signature }}" alt="Client Signature"
                                style="max-height: 100px; border: 1px dashed #ccc;">
                        @else
                            <p class="text-muted">No signature provided.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- E-Sign Modal -->
        <div class="modal fade" id="signModal{{ $feedback->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark">E-Sign Feedback #{{ $feedback->ticket->request_number }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('reports.csf.sign', $feedback->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <p class="text-muted">Draw your signature in the box below:</p>
                            <div class="signature-pad-wrapper"
                                style="border: 1px solid #ccc; border-radius: 4px; overflow: hidden;">
                                <canvas id="signaturePad{{ $feedback->id }}" width="400" height="150"
                                    style="width: 100%; height: 150px;"></canvas>
                            </div>
                            <input type="hidden" name="signature" id="signatureInput{{ $feedback->id }}">
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2"
                                onclick="clearSignature({{ $feedback->id }})">
                                <i class="mdi mdi-eraser"></i> Clear
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning" onclick="saveSignature({{ $feedback->id }})">
                                <i class="mdi mdi-check"></i> Save Signature
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/signature_pad/signature_pad.min.js') }}"></script>
    <script>
        var signaturePads = {};

        $(document).ready(function () {
            // Initialize DataTable when feedback tab is shown
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                if (e.target.id === 'feedback-tab') {
                    if (!$.fn.DataTable.isDataTable('#feedback-table')) {
                        $('#feedback-table').DataTable({
                            "paging": true,
                            "pageLength": 50,
                            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                            "info": true,
                            "searching": true
                        });
                    }
                }
            });

            // Initialize signature pads when modals are shown
            $('[id^="signModal"]').on('shown.bs.modal', function () {
                var feedbackId = $(this).attr('id').replace('signModal', '');
                var canvas = document.getElementById('signaturePad' + feedbackId);
                if (canvas && !signaturePads[feedbackId]) {
                    signaturePads[feedbackId] = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)',
                        penColor: 'rgb(0, 0, 0)'
                    });
                }
            });
        });

        function clearSignature(feedbackId) {
            if (signaturePads[feedbackId]) {
                signaturePads[feedbackId].clear();
            }
        }

        function saveSignature(feedbackId) {
            if (signaturePads[feedbackId] && !signaturePads[feedbackId].isEmpty()) {
                var dataUrl = signaturePads[feedbackId].toDataURL('image/png');
                $('#signatureInput' + feedbackId).val(dataUrl);
            }
        }
    </script>
@endpush