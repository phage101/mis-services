@extends('layouts.nice-admin')

@section('title', 'Show User')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h4 class="card-title mb-0">User Details</h4>
                        <a class="btn btn-secondary btn-sm" href="{{ route('users.index') }}">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Name:</label>
                            <p class="form-control-plaintext border-bottom">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Email:</label>
                            <p class="form-control-plaintext border-bottom">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Office:</label>
                            <p class="form-control-plaintext border-bottom">{{ $user->office->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-dark">Division:</label>
                            <p class="form-control-plaintext border-bottom">{{ $user->division->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="font-weight-bold text-dark">Roles:</label>
                            <div>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $v)
                                        <span class="badge badge-success">{{ $v }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No roles assigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection