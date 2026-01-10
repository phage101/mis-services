@extends('layouts.nice-admin')

@section('title', 'Admin Edit Ticket')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Edit Ticket #{{ $ticket->id }}</h4>
                        <a class="btn btn-secondary" href="{{ route('tickets.show', $ticket) }}"><i
                                class="mdi mdi-arrow-left"></i> Back to Details</a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="requestor_id" class="form-label">Requestor</label>
                                <select name="requestor_id" id="requestor_id" class="form-control select2" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $ticket->requestor_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="request_type_id" class="form-label">Request Type</label>
                                <select name="request_type_id" id="request_type_id" class="form-control" required>
                                    <option value="">Select Request Type</option>
                                    @foreach($requestTypes as $type)
                                        <option value="{{ $type->id }}" {{ $ticket->request_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($ticket->requestType->categories as $category)
                                        <option value="{{ $category->id }}" {{ $ticket->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $ticket->status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="urgency" class="form-label">Urgency</label>
                                <select name="urgency" id="urgency" class="form-control" required>
                                    @foreach($urgencies as $value => $label)
                                        <option value="{{ $value }}" {{ $ticket->urgency == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="datetime_started" class="form-label">Datetime Started (Optional)</label>
                                <input type="datetime-local" name="datetime_started" id="datetime_started"
                                    class="form-control"
                                    value="{{ $ticket->datetime_started ? $ticket->datetime_started->format('Y-m-d\TH:i') : '' }}">
                                <small class="text-muted">Auto-set when status becomes "On-going"</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="datetime_ended" class="form-label">Datetime Ended (Optional)</label>
                                <input type="datetime-local" name="datetime_ended" id="datetime_ended" class="form-control"
                                    value="{{ $ticket->datetime_ended ? $ticket->datetime_ended->format('Y-m-d\TH:i') : '' }}">
                                <small class="text-muted">Auto-set when status becomes "Completed"</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="channel" class="form-label">Channel (Optional)</label>
                                <input type="text" name="channel" id="channel" class="form-control"
                                    value="{{ old('channel', $ticket->channel) }}" placeholder="e.g. Email, Phone, Walk-in">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Admin Remarks (Optional)</label>
                            <textarea name="remarks" id="remarks" class="form-control"
                                rows="4">{{ old('remarks', $ticket->remarks) }}</textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Update Ticket
                                Info</button>
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
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select Requestor'
            });

            $('#request_type_id').on('change', function () {
                var typeId = $(this).val();
                var $categorySelect = $('#category_id');

                $categorySelect.empty().append('<option value="">Select Category</option>');

                if (typeId) {
                    $categorySelect.prop('disabled', true);
                    $.ajax({
                        url: '/api/request-types/' + typeId + '/categories',
                        type: 'GET',
                        success: function (data) {
                            $.each(data, function (key, category) {
                                $categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
                            });
                            $categorySelect.prop('disabled', false);
                        },
                        error: function () {
                            alert('Error loading categories');
                        }
                    });
                } else {
                    $categorySelect.prop('disabled', true);
                }
            });
        });
    </script>
@endpush