@extends('layouts.nice-admin')

@section('title', 'Events Management')

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-md-3">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-info text-white"><i
                                    class="ti-layers"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total Events</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-3">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-secondary text-white"><i
                                    class="ti-timer"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Upcoming</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['upcoming'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-3">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-primary text-white"><i
                                    class="ti-reload"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Ongoing</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['ongoing'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-3">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-success text-white"><i
                                    class="ti-check"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Completed</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['completed'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Events List</h4>
                        @can('create', App\Models\Event::class)
                            <a href="{{ route('events.create') }}" class="btn btn-info">
                                <i class="mdi mdi-plus"></i> Create New Event
                            </a>
                        @endcan
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="events-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Event Title</th>
                                    <th>Type</th>
                                    <th>Venue</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $key => $event)
                                    <tr>
                                        <td>{{ ++$key }}</td>
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

                                        <td class="text-right" style="white-space:nowrap;">
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info"
                                                title="View">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @can('update', $event)
                                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning"
                                                    title="Edit">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete', $event)
                                                <form action="{{ route('events.destroy', $event) }}" method="POST"
                                                    style="display:inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            @endcan
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#events-table').DataTable({
                "paging": true,
                "info": true
            });
        });
    </script>
@endpush