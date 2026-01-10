@extends('layouts.nice-admin')

@section('title', 'Meeting Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Meeting: {{ $meeting->topic }}</h4>
                        <a class="btn btn-secondary" href="{{ route('meetings.index') }}"><i class="mdi mdi-arrow-left"></i>
                            Back</a>
                    </div>

                    @if($meeting->description)
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Description:</strong></div>
                            <div class="col-md-8">{{ $meeting->description }}</div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Date Requested:</strong></div>
                        <div class="col-md-8">{{ $meeting->date_requested->format('F d, Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Requestor:</strong></div>
                        <div class="col-md-8">{{ $meeting->requestor->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Status:</strong></div>
                        <div class="col-md-8">
                            @php
                                $statusBadge = [
                                    'pending' => 'badge-secondary',
                                    'scheduled' => 'badge-success',
                                    'conflict' => 'badge-danger',
                                    'cancelled' => 'badge-warning'
                                ][$meeting->status] ?? 'badge-secondary';
                            @endphp
                            <span class="badge {{ $statusBadge }}">{{ ucfirst($meeting->status) }}</span>
                        </div>
                    </div>

                    @if($meeting->status === 'scheduled')
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Meeting Platform:</strong></div>
                            <div class="col-md-8">{{ $meeting->platform->name ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Meeting Host:</strong></div>
                            <div class="col-md-8">{{ $meeting->host->name ?? 'N/A' }}</div>
                        </div>
                        @if($meeting->meeting_details)
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <strong>Meeting Details:</strong>
                                    <button class="btn btn-outline-info btn-xs ml-2" id="copyDetailsBtn" title="Copy to clipboard">
                                        <i class="mdi mdi-content-copy"></i> Copy
                                    </button>
                                </div>
                                <div class="col-md-8 bg-light p-2 border rounded" id="meetingDetailsContent">
                                    {!! nl2br(e($meeting->meeting_details)) !!}
                                </div>
                            </div>
                        @endif
                    @endif

                    <hr>

                    <h5><strong>Requested Time Slots:</strong></h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mt-2">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($meeting->slots as $slot)
                                    <tr>
                                        <td>{{ $slot->meeting_date->format('F d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('Admin'))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Admin Actions</h4>
                        <a href="{{ route('meetings.edit', $meeting->id) }}" class="btn btn-warning btn-block"><i
                                class="mdi mdi-pencil"></i> Process Request</a>
                        <hr>
                        <form action="{{ route('meetings.destroy', $meeting->id) }}" method="POST"
                            onsubmit="return confirm('Delete this request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block"><i class="mdi mdi-delete"></i> Delete
                                Request</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#copyDetailsBtn').on('click', function () {
                var $btn = $(this);
                var originalText = $btn.html();
                var detailsText = $('#meetingDetailsContent').text().trim();

                // Create a temporary textarea to hold the text
                var $temp = $("<textarea>");
                $("body").append($temp);
                $temp.val(detailsText).select();
                document.execCommand("copy");
                $temp.remove();

                // Show success feedback
                $btn.html('<i class="mdi mdi-check"></i> Copied!').removeClass('btn-outline-info').addClass('btn-success');

                setTimeout(function () {
                    $btn.html(originalText).removeClass('btn-success').addClass('btn-outline-info');
                }, 2000);
            });
        });
    </script>
@endpush