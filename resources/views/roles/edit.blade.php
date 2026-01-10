@extends('layouts.nice-admin')

@section('title', 'Edit Role')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Edit Role</h4>
                        <a class="btn btn-secondary" href="{{ route('roles.index') }}"><i class="mdi mdi-arrow-left"></i>
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

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $role->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="row">
                                @foreach($permission as $perm)
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permission[]" value="{{ $perm->id }}"
                                                class="custom-control-input" id="perm_{{ $perm->id }}" {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection