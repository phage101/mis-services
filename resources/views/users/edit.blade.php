@extends('layouts.nice-admin')

@section('title', 'Edit User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Edit User</h4>
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

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password (Optional)</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Leave blank to keep current">
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password (Optional)</label>
                            <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="office_id">Office</label>
                                    <select name="office_id" id="office_id" class="form-control" required>
                                        <option value="">Select Office</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" {{ old('office_id', $user->office_id) == $office->id ? 'selected' : '' }}>
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
                                            <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
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
                                    <option value="{{ $id }}" {{ in_array($id, $userRole) ? 'selected' : '' }}>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Update</button>
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