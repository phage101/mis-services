@extends('layouts.nice-admin')

@section('title', 'Create New Ticket')

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
                        <h4 class="card-title mb-0">Submit New Request</h4>
                        <a class="btn btn-secondary" href="{{ route('tickets.index') }}"><i class="mdi mdi-arrow-left"></i>
                            Back</a>
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

                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf
                        @if(Auth::user()->hasRole('Admin'))
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="requestor_id" class="form-label">Requestor</label>
                                    <select name="requestor_id" id="requestor_id" class="form-control select2" required>
                                        <option value="">Select Requestor</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('requestor_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_requested" class="form-label">Date Requested</label>
                                <input type="date" name="date_requested" id="date_requested" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="urgency" class="form-label">Urgency</label>
                                <select name="urgency" id="urgency" class="form-control" required>
                                    @foreach($urgencies as $value => $label)
                                        <option value="{{ $value }}" {{ $value == 'medium' ? 'selected' : '' }}>{{ $label }}
                                        </option>
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
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-control" required disabled>
                                    <option value="">Select Category</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="complaint" class="form-label">Complaint / Description</label>
                            <textarea name="complaint" id="complaint" class="form-control" rows="5"
                                placeholder="Detailed description of your request or issue" required></textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Submit
                                Ticket</button>
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
                    var url = "{{ route('api.categories', ':id') }}";
                    url = url.replace(':id', typeId);

                    $.ajax({
                        url: url,
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