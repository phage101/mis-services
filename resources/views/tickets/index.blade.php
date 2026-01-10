@extends('layouts.nice-admin')

@section('title', 'Ticketing System')

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
                                    class="ti-ticket"></i></span></div>
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
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-primary text-white"><i
                                    class="ti-reload"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">On-going</h6>
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
                        <h4 class="card-title mb-0">Ticket List</h4>
                        <a class="btn btn-info" href="{{ route('tickets.create') }}"><i class="mdi mdi-plus"></i> Create New
                            Ticket</a>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="tickets-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date Requested</th>
                                    <th>Requestor</th>
                                    <th>Type</th>
                                    <th>Category</th>
                                    <th>Urgency</th>
                                    <th>Status</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $key => $ticket)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $ticket->date_requested->format('Y-m-d') }}</td>
                                        <td>{{ $ticket->requestor->name }}</td>
                                        <td>{{ $ticket->requestType->name ?? 'N/A' }}</td>
                                        <td>{{ $ticket->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $urgencyBadge = [
                                                    'low' => 'badge-info',
                                                    'medium' => 'badge-primary',
                                                    'high' => 'badge-warning',
                                                    'critical' => 'badge-danger'
                                                ][$ticket->urgency] ?? 'badge-secondary';
                                            @endphp
                                            <span class="badge {{ $urgencyBadge }}">{{ ucfirst($ticket->urgency) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusBadge = [
                                                    'pending' => 'badge-secondary',
                                                    'on-going' => 'badge-info',
                                                    'completed' => 'badge-success',
                                                    'cancelled' => 'badge-danger'
                                                ][$ticket->status] ?? 'badge-secondary';
                                            @endphp
                                            <span class="badge {{ $statusBadge }}">{{ ucfirst($ticket->status) }}</span>
                                        </td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            <a class="btn btn-info btn-sm" href="{{ route('tickets.show', $ticket) }}"><i
                                                    class="mdi mdi-eye"></i></a>
                                            @if(Auth::user()->hasRole('Admin'))
                                                <a class="btn btn-warning btn-sm" href="{{ route('tickets.edit', $ticket) }}"><i
                                                        class="mdi mdi-pencil"></i></a>
                                                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                                                    style="display:inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="mdi mdi-delete"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {!! $tickets->links() !!}
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
            $('#tickets-table').DataTable({
                "paging": false, // Handled by Laravel pagination
                "info": false
            });
        });
    </script>
@endpush