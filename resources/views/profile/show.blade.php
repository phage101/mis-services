@extends('layouts.nice-admin')

@section('title', 'My Profile')

@section('content')
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width: 150px; height: 150px; font-size: 50px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 class="card-title m-t-10">{{ $user->name }}</h4>
                        <h6 class="card-subtitle">{{ $user->roles->pluck('name')->implode(', ') }}</h6>
                    </center>
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body">
                    <small class="text-muted">Email address </small>
                    <h6>{{ $user->email }}</h6>
                    <small class="text-muted p-t-30 db">Account Created</small>
                    <h6>{{ $user->created_at->format('M d, Y') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" class="form-horizontal form-material">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label class="col-md-12">Full Name</label>
                            <div class="col-md-12">
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control form-control-line" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="example-email" class="col-md-12">Email</label>
                            <div class="col-md-12">
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control form-control-line" name="example-email" id="example-email" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">New Password (leave blank to keep current)</label>
                            <div class="col-md-12">
                                <input type="password" name="password" class="form-control form-control-line">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Confirm Password</label>
                            <div class="col-md-12">
                                <input type="password" name="password_confirmation" class="form-control form-control-line">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection