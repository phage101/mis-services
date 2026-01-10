@extends('layouts.nice-admin')

@section('title', 'Request Meeting')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }

        .slot-row {
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .slot-row:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Meeting Request Form</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('meetings.store') }}" method="POST">
                        @csrf
                        @if(Auth::user()->hasRole('Admin'))
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="requestor_id" class="form-label">Requestor</label>
                                    <select name="requestor_id" id="requestor_id" class="form-control select2" required>
                                        <option value="">Select Requestor</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('requestor_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="topic" class="form-label">Meeting Topic</label>
                                <input type="text" name="topic" id="topic" class="form-control" required
                                    placeholder="e.g. Project Kickoff">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description / Agenda (Optional)</label>
                                <textarea name="description" id="description" class="form-control" rows="3"
                                    placeholder="Provide more context or agenda for the meeting..."></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Requested Date & Time Slots</label>
                                <input type="hidden" name="date_requested" value="{{ date('Y-m-d') }}">
                                <div id="slots-container">
                                    <div class="row slot-row">
                                        <div class="col-md-4">
                                            <input type="date" name="slots[0][date]" class="form-control"
                                                min="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" name="slots[0][start]" class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" name="slots[0][end]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <button type="button" class="btn btn-success add-slot"><i
                                                    class="mdi mdi-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Submit
                                Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

            let slotCount = 1;
            $(document).on('click', '.add-slot', function () {
                const newSlot = `
                        <div class="row slot-row">
                            <div class="col-md-4">
                                <input type="date" name="slots[${slotCount}][date]" class="form-control" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="time" name="slots[${slotCount}][start]" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <input type="time" name="slots[${slotCount}][end]" class="form-control" required>
                            </div>
                            <div class="col-md-2 text-right">
                                <button type="button" class="btn btn-danger remove-slot"><i class="mdi mdi-minus"></i></button>
                            </div>
                        </div>
                    `;
                $('#slots-container').append(newSlot);
                slotCount++;
            });

            $(document).on('click', '.remove-slot', function () {
                $(this).closest('.slot-row').remove();
            });
        });
    </script>
@endpush