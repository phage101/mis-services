@extends('layouts.nice-admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="m-r-15">
                            <span class="btn btn-circle btn-lg bg-white op-5 text-white">
                                <i class="ti-user"></i>
                            </span>
                        </div>
                        <div>
                            <h3 class="mb-0 text-white">Welcome back, {{ $user->name }}!</h3>
                            <p class="mb-0 op-7">
                                {{ $user->office->name ?? 'No Office' }} | {{ $user->division->name ?? 'No Division' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if($isAdmin)
            <!-- Card 1: Total Users -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover">
                    <div class="box bg-cyan text-center">
                        <h1 class="font-light text-white"><i class="mdi mdi-account-multiple"></i></h1>
                        <h6 class="text-white">Total Users</h6>
                        <h3 class="text-white">{{ number_format($kpis['users']) }}</h3>
                    </div>
                </div>
            </div>
        @else
            <!-- Card 1: My Profile -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('profile.show') }}">
                    <div class="card card-hover">
                        <div class="box bg-cyan text-center">
                            <h1 class="font-light text-white"><i class="mdi mdi-account-card-details"></i></h1>
                            <h6 class="text-white">My Profile</h6>
                            <h3 class="text-white">Settings</h3>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        <!-- Card 2: Pending Tickets -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-danger text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-ticket"></i></h1>
                    <h6 class="text-white">{{ $isAdmin ? 'Pending Tickets' : 'My Pending Tickets' }}</h6>
                    <h3 class="text-white">{{ number_format($kpis['tickets']) }}</h3>
                </div>
            </div>
        </div>
        <!-- Card 3: Scheduled Meetings -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-success text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-calendar-check"></i></h1>
                    <h6 class="text-white">{{ $isAdmin ? 'Scheduled Meetings' : 'My Scheduled Meetings' }}</h6>
                    <h3 class="text-white">{{ number_format($kpis['meetings']) }}</h3>
                </div>
            </div>
        </div>
        <!-- Card 4: Upcoming Events -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-layers"></i></h1>
                    <h6 class="text-white">Upcoming Events</h6>
                    <h3 class="text-white">{{ number_format($kpis['events']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    @if(!$isAdmin)
        <!-- Quick Actions for Non-Admins -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h4 class="card-title">Quick Actions</h4>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('tickets.create') }}" class="btn btn-danger m-r-5">
                                <i class="mdi mdi-ticket-plus"></i> Create New Ticket
                            </a>
                            <a href="{{ route('meetings.create') }}" class="btn btn-success">
                                <i class="mdi mdi-calendar-plus"></i> Request a Meeting
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Activity Section -->
    <div class="row">
        <!-- Recent Tickets -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">{{ $isAdmin ? 'Recent Tickets' : 'My Recent Tickets' }}</h4>
                        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Topic</th>
                                    <th>Requestor</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTickets as $ticket)
                                    <tr>
                                        <td>
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="font-medium text-dark">{{ $ticket->topic }}</a>
                                        </td>
                                        <td>{{ $ticket->requestor->name ?? 'N/A' }}</td>
                                        <td class="text-muted"><small>{{ $ticket->created_at->diffForHumans() }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No recent tickets</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Meetings -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">{{ $isAdmin ? 'Upcoming Meetings' : 'My Upcoming Meetings' }}</h4>
                        <a href="{{ route('meetings.index') }}" class="btn btn-sm btn-link">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Meeting</th>
                                    <th>Host</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingMeetings as $meeting)
                                    <tr>
                                        <td>
                                            <a href="{{ route('meetings.show', $meeting) }}"
                                                class="font-medium text-dark">{{ $meeting->topic }}</a>
                                        </td>
                                        <td>{{ $meeting->host->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-success">Scheduled</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No upcoming meetings</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($isAdmin)
        <!-- Latest Registered Participants -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Latest Registered Participants</h4>
                            <a href="{{ route('events.index') }}" class="btn btn-sm btn-link">Manage Events</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Participant Name</th>
                                        <th>Event</th>
                                        <th>Organization</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($latestParticipants as $participant)
                                        <tr>
                                            <td class="font-medium">{{ $participant->name }}</td>
                                            <td>
                                                <a href="{{ route('events.show', $participant->event) }}"
                                                    class="text-dark">{{ $participant->event->title ?? 'N/A' }}</a>
                                            </td>
                                            <td>{{ $participant->organization ?? 'N/A' }}</td>
                                            <td class="text-muted">
                                                <small>{{ $participant->created_at->format('M d, Y h:i A') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No participants registered yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection