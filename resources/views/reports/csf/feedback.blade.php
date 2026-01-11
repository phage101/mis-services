@extends('layouts.nice-admin')

@section('title', 'CSF Feedback List')

@section('breadcrumb-title', 'Client Satisfaction Feedback')

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('reports.csf.dashboard') }}">Reports</a></li>
    <li class="breadcrumb-item active">Feedback List</li>
@endsection

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Client Feedback List</h4>
                    </div>

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

                                            <!-- Modal -->
                                            <div class="modal fade text-left" id="feedbackModal{{ $feedback->id }}"
                                                tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Feedback Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><strong>Ticket:</strong>
                                                                        {{ $feedback->ticket->request_number }}</p>
                                                                    <p><strong>Client Name:</strong>
                                                                        {{ $feedback->ticket->requestor->name ?? 'N/A' }}</p>
                                                                    <p><strong>Client Type:</strong>
                                                                        {{ $feedback->ticket->requestor->client_type ?? 'N/A' }}
                                                                    </p>
                                                                    <p><strong>Email:</strong>
                                                                        {{ $feedback->ticket->requestor->email ?? 'N/A' }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p><strong>Date Submitted:</strong>
                                                                        {{ $feedback->created_at->format('F d, Y h:i A') }}</p>
                                                                    <p><strong>Sex:</strong>
                                                                        {{ $feedback->ticket->requestor->sex ?? 'N/A' }}</p>
                                                                    <p><strong>Age Bracket:</strong>
                                                                        {{ $feedback->ticket->requestor->age_bracket ?? 'N/A' }}
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <hr>
                                                            <h6><strong>Citizen's Charter Awareness:</strong></h6>
                                                            <table class="table table-sm table-bordered">
                                                                <tr>
                                                                    <td>CC1 - Awareness of CC</td>
                                                                    <td class="text-right">
                                                                        {{ $feedback->cc1_awareness ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>CC2 - Saw CC Posted</td>
                                                                    <td class="text-right">
                                                                        {{ $feedback->cc2_visibility ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>CC3 - CC Helped in Transaction</td>
                                                                    <td class="text-right">
                                                                        {{ $feedback->cc3_helpfulness ?? 'N/A' }}</td>
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
                                                                                class="badge badge-info">{{ $feedback->rating_responsiveness ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Reliability</td>
                                                                        <td class="text-right"><span
                                                                                class="badge badge-info">{{ $feedback->rating_reliability ?? '-' }}</span>
                                                                        </td>
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
                                                                                class="badge badge-info">{{ $feedback->rating_communication ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Costs</td>
                                                                        <td class="text-right"><span
                                                                                class="badge badge-info">{{ $feedback->rating_costs ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Integrity</td>
                                                                        <td class="text-right"><span
                                                                                class="badge badge-info">{{ $feedback->rating_integrity ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Assurance</td>
                                                                        <td class="text-right"><span
                                                                                class="badge badge-info">{{ $feedback->rating_assurance ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Outcome</td>
                                                                        <td class="text-right"><span
                                                                                class="badge badge-info">{{ $feedback->rating_outcome ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Resource Speaker (if applicable)</td>
                                                                        <td class="text-right"><span
                                                                                class="badge badge-info">{{ $feedback->rating_resource_speaker ?? '-' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="table-success font-weight-bold">
                                                                        <td>Overall Rating</td>
                                                                        <td class="text-right"><span class="badge badge-success"
                                                                                style="font-size: 1rem;">{{ $feedback->rating_overall }}
                                                                                / 5</span></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            @if($feedback->rating_remarks)
                                                                <h6><strong>Reasons for Rating:</strong></h6>
                                                                <p class="alert alert-light">{{ $feedback->rating_remarks }}</p>
                                                            @endif

                                                            <hr>
                                                            <h6><strong>Comments / Suggestions:</strong></h6>
                                                            <p class="alert alert-light">
                                                                {{ $feedback->comments ?? 'No comments.' }}</p>

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
                                            <div class="modal fade" id="signModal{{ $feedback->id }}" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title text-dark">E-Sign Feedback #{{ $feedback->ticket->request_number }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('reports.csf.sign', $feedback->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <p class="text-muted">Draw your signature in the box below:</p>
                                                                <div class="signature-pad-wrapper" style="border: 1px solid #ccc; border-radius: 4px; overflow: hidden;">
                                                                    <canvas id="signaturePad{{ $feedback->id }}" width="400" height="150" style="width: 100%; height: 150px;"></canvas>
                                                                </div>
                                                                <input type="hidden" name="signature" id="signatureInput{{ $feedback->id }}">
                                                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="clearSignature({{ $feedback->id }})">
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/signature_pad/signature_pad.min.js') }}"></script>
    <script>
        var signaturePads = {};

        $(document).ready(function () {
            $('#feedback-table').DataTable({
                "paging": true,
                "pageLength": 50,
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                "info": true,
                "searching": true
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