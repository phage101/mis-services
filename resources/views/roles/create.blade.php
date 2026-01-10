@extends('layouts.nice-admin')

@section('title', 'Create Role')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Create New Role</h4>
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

                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Role Name"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="row">
                                @foreach($permission as $perm)
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permission[]" value="{{ $perm->id }}"
                                                class="custom-control-input" id="perm_{{ $perm->id }}">
                                            <label class="custom-control-label"
                                                for="perm_{{ $perm->id }}">{{ $perm->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info"><i class="mdi mdi-content-save"></i> Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection