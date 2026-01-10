@extends('layouts.nice-admin')

@section('title', 'Ticket Details')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Ticket #{{ $ticket->id }}</h4>
                        <a class="btn btn-secondary" href="{{ route('tickets.index') }}"><i class="mdi mdi-arrow-left"></i>
                            Back</a>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Date Requested:</strong></div>
                        <div class="col-md-8">{{ $ticket->date_requested->format('F d, Y') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Requestor:</strong></div>
                        <div class="col-md-8">{{ $ticket->requestor->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Type & Category:</strong></div>
                        <div class="col-md-8">{{ $ticket->requestType->name ?? 'N/A' }} /
                            {{ $ticket->category->name ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Urgency:</strong></div>
                        <div class="col-md-8">
                            @php
                                $urgencyBadge = [
                                    'low' => 'badge-info',
                                    'medium' => 'badge-primary',
                                    'high' => 'badge-warning',
                                    'critical' => 'badge-danger'
                                ][$ticket->urgency] ?? 'badge-secondary';
                            @endphp
                            <span class="badge {{ $urgencyBadge }}">{{ ucfirst($ticket->urgency) }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Status:</strong></div>
                        <div class="col-md-8">
                            @php
                                $statusBadge = [
                                    'pending' => 'badge-secondary',
                                    'on-going' => 'badge-info',
                                    'completed' => 'badge-success',
                                    'cancelled' => 'badge-danger'
                                ][$ticket->status] ?? 'badge-secondary';
                            @endphp
                            <span class="badge {{ $statusBadge }}">{{ ucfirst($ticket->status) }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h5><strong>Complaint:</strong></h5>
                        <p class="p-2 bg-light border rounded">{{ $ticket->complaint }}</p>
                    </div>

                    @if($ticket->remarks)
                        <div class="mb-3">
                            <h5><strong>Admin Remarks:</strong></h5>
                            <p class="p-2 bg-light border border-info rounded">{{ $ticket->remarks }}</p>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6 text-muted">
                            <small>Started:
                                {{ $ticket->datetime_started ? $ticket->datetime_started->format('Y-m-d H:i') : 'N/A' }}</small>
                        </div>
                        <div class="col-md-6 text-muted text-right">
                            <small>Ended:
                                {{ $ticket->datetime_ended ? $ticket->datetime_ended->format('Y-m-d H:i') : 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responses History -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Response History</h4>
                    <div class="steamline mt-4">
                        @forelse($ticket->responses as $response)
                            <div class="sl-item border-left border-info pl-3 pb-3">
                                <div class="sl-content">
                                    <div class="text-muted"><small>{{ $response->created_at->diffForHumans() }} by
                                            {{ $response->user->name }} (Status: {{ ucfirst($response->status) }})</small></div>
                                    <div class="mt-2">{{ $response->action_taken }}</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No responses yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('Admin'))
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Response</h4>
                        <form action="{{ route('tickets.response', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="status">Change Status</label>
                                <select name="status" id="status" class="form-control">
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $ticket->status == $value ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="action_taken">Action Taken / Response</label>
                                <textarea name="action_taken" id="action_taken" class="form-control" rows="4"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-info btn-block">Submit Response</button>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="card-title">Admin Quick Edit</h4>
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning btn-block">Full Edit</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection