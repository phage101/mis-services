@extends('layouts.nice-admin')

@section('title', 'Process Meeting Request')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Process Meeting #{{ $meeting->id }}</h4>

                    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $meeting->status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="platform_id" class="form-label">Platform (Optional)</label>
                                <select name="platform_id" id="platform_id" class="form-control">
                                    <option value="">-- Select Platform --</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}" {{ ($meeting->platform_id ?? 1) == $platform->id ? 'selected' : '' }}>{{ $platform->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="host_id" class="form-label">Host (Optional)</label>
                                <select name="host_id" id="host_id" class="form-control">
                                    <option value="">-- Select Host --</option>
                                    @foreach($hosts as $host)
                                        <option value="{{ $host->id }}" {{ $meeting->host_id == $host->id ? 'selected' : '' }}>
                                            {{ $host->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Requested Time Slots & Availability Check</label>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Time Range</th>
                                                <th>Conflict Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($meeting->slots as $slot)
                                                <tr>
                                                    <td>{{ $slot->meeting_date->format('Y-m-d') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                                    </td>
                                                    <td>
                                                        <div id="conflict-badge-{{ $slot->id }}" class="conflict-badge"
                                                            data-slot-id="{{ $slot->id }}">
                                                            <span class="badge badge-secondary">Host not selected</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description / Agenda (Optional)</label>
                            <textarea name="description" id="description" class="form-control"
                                rows="3">{{ old('description', $meeting->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="meeting_details" class="form-label">Meeting Details (Optional)</label>
                            <textarea name="meeting_details" id="meeting_details" class="form-control"
                                rows="4">{{ old('meeting_details', $meeting->meeting_details) }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            function checkConflicts() {
                const hostId = $('#host_id').val();

                if (!hostId) {
                    $('.conflict-badge').html('<span class="badge badge-secondary">Host not selected</span>');
                    return;
                }

                $('.conflict-badge').each(function () {
                    const $badgeDiv = $(this);
                    const slotId = $badgeDiv.data('slot-id');

                    $badgeDiv.html('<span class="badge badge-info">Checking...</span>');

                    $.ajax({
                        url: '{{ route("api.meetings.conflict") }}',
                        data: {
                            host_id: hostId,
                            slot_id: slotId,
                            meeting_id: '{{ $meeting->id }}'
                        },
                        success: function (data) {
                            if (data.conflict) {
                                $badgeDiv.html('<span class="badge badge-danger"><i class="mdi mdi-alert"></i> Conflict!</span>');
                            } else {
                                $badgeDiv.html('<span class="badge badge-success"><i class="mdi mdi-check"></i> Available</span>');
                            }
                        }
                    });
                });
            }

            $('#host_id, #status').on('change', checkConflicts);

            // Initial check
            if ($('#host_id').val()) {
                checkConflicts();
            }
        });
    </script>
@endpush