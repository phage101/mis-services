@extends('layouts.nice-admin')

@section('title', 'Show Role')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h4 class="card-title mb-0">Role Details</h4>
                        <a class="btn btn-secondary btn-sm" href="{{ route('roles.index') }}">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold text-dark">Name:</label>
                            <p class="form-control-plaintext border-bottom">{{ $role->name }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold text-dark">Permissions:</label>
                            <div class="p-3 bg-light border rounded">
                                @if(!empty($rolePermissions))
                                    @foreach($rolePermissions as $v)
                                        <span class="badge badge-primary mr-1 mb-1">{{ $v->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No permissions assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection