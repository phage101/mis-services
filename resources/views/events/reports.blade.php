@extends('layouts.nice-admin')

@section('title', 'Event Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Event Summary Report</h4>
                
                <form action="{{ route('events.reports') }}" method="GET" class="row bg-light p-3 rounded mb-4 no-gutters">
                    <div class="col-md-4 px-2">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4 px-2">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 px-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-info btn-block"><i class="mdi mdi-filter"></i> Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Date</th>
                                <th>Event Title</th>
                                <th>Type</th>
                                <th class="text-center">Expected</th>
                                <th class="text-center">Registered</th>
                                <th class="text-center">Actual Present</th>
                                <th class="text-center">Attendance %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $totalExpected = 0;
                                $totalRegistered = 0;
                                $totalPresent = 0;
                            @endphp
                            @forelse($events as $event)
                                @php
                                    $totalExpected += $event->expected_participants;
                                    $totalRegistered += $event->participants_count;
                                    $totalPresent += $event->present_count;
                                    $percentage = $event->participants_count > 0 ? ($event->present_count / $event->participants_count) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $event->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $event->title }}</td>
                                    <td>{{ $event->event_type }}</td>
                                    <td class="text-center">{{ $event->expected_participants }}</td>
                                    <td class="text-center">{{ $event->participants_count }}</td>
                                    <td class="text-center text-info font-weight-bold">{{ $event->present_count }}</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar {{ $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                                 role="progressbar" style="width: {{ $percentage }}%" 
                                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small>{{ number_format($percentage, 1) }}%</small>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No events found for this period.</td></tr>
                            @endforelse
                        </tbody>
                        @if($events->count() > 0)
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="3" class="text-right">Grand Totals:</td>
                                    <td class="text-center">{{ $totalExpected }}</td>
                                    <td class="text-center">{{ $totalRegistered }}</td>
                                    <td class="text-center text-info">{{ $totalPresent }}</td>
                                    <td class="text-center">
                                        {{ $totalRegistered > 0 ? number_format(($totalPresent / $totalRegistered) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                <div class="mt-4 no-print">
                    <button onclick="window.print();" class="btn btn-outline-secondary">
                        <i class="mdi mdi-printer"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .no-print, .left-sidebar, .topbar, .page-breadcrumb, .footer {
            display: none !important;
        }
        .page-wrapper {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
