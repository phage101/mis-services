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
                        <div class="form-group">
                            <label for="roles">Role</label>
                            <select name="roles[]" id="roles" class="form-control" multiple>
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