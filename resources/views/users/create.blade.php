@extends('layouts.nice-admin')

@section('title', 'Create User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Create New User</h4>
                        <a class="btn btn-secondary" href="{{ route('users.index') }}"><i class="mdi mdi-arrow-left"></i>
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

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control"
                                placeholder="Confirm password" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_type">Client Type</label>
                                    <select name="client_type" id="client_type" class="form-control" required>
                                        <option value="">Select Client Type</option>
                                        <option value="Citizen" {{ old('client_type') == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                        <option value="Business" {{ old('client_type') == 'Business' ? 'selected' : '' }}>Business</option>
                                        <option value="Government Employee or other agency" {{ old('client_type') == 'Government Employee or other agency' ? 'selected' : '' }}>Government Employee or other agency</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sex">Sex</label>
                                    <select name="sex" id="sex" class="form-control" required>
                                        <option value="">Select Sex</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age_bracket">Age Bracket</label>
                                    <select name="age_bracket" id="age_bracket" class="form-control" required>
                                        <option value="">Select Age Bracket</option>
                                        <option value="19 or lower" {{ old('age_bracket') == '19 or lower' ? 'selected' : '' }}>19 or lower</option>
                                        <option value="20–34" {{ old('age_bracket') == '20–34' ? 'selected' : '' }}>20–34</option>
                                        <option value="35–49" {{ old('age_bracket') == '35–49' ? 'selected' : '' }}>35–49</option>
                                        <option value="50–64" {{ old('age_bracket') == '50–64' ? 'selected' : '' }}>50–64</option>
                                        <option value="65 or higher" {{ old('age_bracket') == '65 or higher' ? 'selected' : '' }}>65 or higher</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_no">Contact Number</label>
                                    <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Enter contact number" value="{{ old('contact_no') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="office_id">Office</label>
                                    <select name="office_id" id="office_id" class="form-control" required>
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                                                {{ $office->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="division_id">Division</label>
                                    <select name="division_id" id="division_id" class="form-control" required>
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="roles">Role</label>
                            <select name="roles[]" id="roles" class="form-control" multiple required>
                                @foreach($roles as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#office_id').change(function() {
        var officeId = $(this).val();
        var divisionSelect = $('#division_id');
        
        divisionSelect.html('<option value="">Loading...</option>');
        
        if (officeId) {
            $.ajax({
                url: '{{ route("offices.divisions", ":id") }}'.replace(':id', officeId),
                type: 'GET',
                success: function(data) {
                    divisionSelect.html('<option value="">Select Division</option>');
                    $.each(data, function(key, value) {
                        divisionSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            divisionSelect.html('<option value="">Select Division</option>');
        }
    });
});
</script>
@endpush