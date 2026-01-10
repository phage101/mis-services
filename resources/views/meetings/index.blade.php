@extends('layouts.nice-admin')

@section('title', 'Meeting Scheduler')

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
    <style>
        .fc-event {
            cursor: pointer;
        }

        .tab-content {
            padding-top: 20px;
        }

        #calendar {
            min-height: 600px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-md-3">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-info text-white"><i
                                    class="ti-calendar"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total</h6>
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
                            <h6 class="card-subtitle text-muted">Pending</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['pending'] }}</h3>
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
                            <h6 class="card-subtitle text-muted">Scheduled</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['scheduled'] }}</h3>
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
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-danger text-white"><i
                                    class="ti-alert"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Conflicts</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['conflict'] }}</h3>
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
                        <h4 class="card-title mb-0">Meeting Scheduler</h4>
                        @can('create', App\Models\Meeting::class)
                            <a class="btn btn-info" href="{{ route('meetings.create') }}"><i class="mdi mdi-plus"></i> Request
                                Meeting</a>
                        @endcan
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif

                    <!-- View Navigation -->
                    <ul class="nav nav-pills custom-pills mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-list-tab" data-toggle="pill" href="#pills-list" role="tab"
                                aria-controls="pills-list" aria-selected="true">
                                <i class="mdi mdi-view-list"></i> List View
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-calendar-tab" data-toggle="pill" href="#pills-calendar" role="tab"
                                aria-controls="pills-calendar" aria-selected="false">
                                <i class="mdi mdi-calendar"></i> Calendar View
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- List View -->
                        <div class="tab-pane fade show active" id="pills-list" role="tabpanel"
                            aria-labelledby="pills-list-tab">
                            <div class="table-responsive">
                                <table id="meetings-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date Requested</th>
                                            <th>Requestor</th>
                                            <th>Topic</th>
                                            <th>Status</th>
                                            <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($meetings as $meeting)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $meeting->date_requested->format('Y-m-d') }}</td>
                                                <td>{{ $meeting->requestor->name }}</td>
                                                <td>{{ $meeting->topic }}</td>
                                                <td>
                                                    @php
                                                        $statusBadge = [
                                                            'pending' => 'badge-secondary',
                                                            'scheduled' => 'badge-success',
                                                            'conflict' => 'badge-danger',
                                                            'cancelled' => 'badge-warning'
                                                        ][$meeting->status] ?? 'badge-secondary';
                                                    @endphp
                                                    <span
                                                        class="badge {{ $statusBadge }}">{{ ucfirst($meeting->status) }}</span>
                                                </td>
                                                <td class="text-right" style="white-space:nowrap;">
                                                    <a class="btn btn-info btn-sm"
                                                        href="{{ route('meetings.show', $meeting) }}"><i
                                                            class="mdi mdi-eye"></i></a>
                                                    @can('update', $meeting)
                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('meetings.edit', $meeting) }}"><i
                                                                class="mdi mdi-pencil"></i></a>
                                                    @endcan
                                                    @can('delete', $meeting)
                                                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST"
                                                            style="display:inline" onsubmit="return confirm('Are you sure?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                                    class="mdi mdi-delete"></i></button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {!! $meetings->links() !!}
                            </div>
                        </div>

                        <!-- Calendar View -->
                        <div class="tab-pane fade" id="pills-calendar" role="tabpanel" aria-labelledby="pills-calendar-tab">
                            <div id='calendar'></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var calendarEl = document.getElementById('calendar');
            var calendar;

            $('#pills-calendar-tab').on('shown.bs.tab', function (e) {
                if (!calendar) {
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        initialView: 'dayGridMonth',
                        events: '{{ route("api.meetings.events") }}',
                        eventClick: function (info) {
                            if (info.event.url) {
                                window.location.href = info.event.url;
                                info.jsEvent.preventDefault();
                            }
                        }
                    });
                    calendar.render();
                } else {
                    calendar.updateSize();
                }
            });

            $('#meetings-table').DataTable({
                "paging": false,
                "info": false
            });
        });
    </script>
@endpush