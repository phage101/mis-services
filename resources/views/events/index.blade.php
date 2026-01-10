@extends('layouts.nice-admin')

@section('title', 'Events Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Events List</h4>
                        <a href="{{ route('events.create') }}" class="btn btn-info">
                            <i class="mdi mdi-plus"></i> Create New Event
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Title</th>
                                    <th>Type</th>
                                    <th>Venue</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            <strong>{{ $event->title }}</strong><br>
                                            <small class="text-muted">by {{ $event->organizer->name }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ ucfirst($event->event_type) }}</span><br>
                                            <small class="text-info">{{ $event->classification }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-outline-info">{{ ucfirst($event->venue_type) }}</span><br>
                                            <small>{{ Str::limit($event->venue_platform, 30) }}</small>
                                        </td>
                                        <td>
                                            @if($event->dates->count() > 1)
                                                <span class="badge badge-info mb-1">Multiple Sessions</span><br>
                                                {{ $event->start_date->format('M d') }} - {{ $event->end_date->format('M d, Y') }}
                                            @else
                                                {{ $event->start_date->format('M d, Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'upcoming' => 'badge-primary',
                                                    'ongoing' => 'badge-info',
                                                    'completed' => 'badge-success',
                                                    'cancelled' => 'badge-danger'
                                                ][$event->status] ?? 'badge-secondary';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst($event->status) }}</span>
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-info"
                                                    title="View">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>
                                                <a href="{{ route('events.edit', $event) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection