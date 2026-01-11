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
                            <label class="col-md-12">Contact Number</label>
                            <div class="col-md-12">
                                <input type="text" name="contact_no" value="{{ old('contact_no', $user->contact_no) }}"
                                    class="form-control form-control-line" placeholder="e.g. 09123456789">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Client Type</label>
                            <div class="col-md-12">
                                <select name="client_type" class="form-control form-control-line">
                                    <option value="">Select...</option>
                                    <option value="Citizen" {{ old('client_type', $user->client_type) == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                    <option value="Business" {{ old('client_type', $user->client_type) == 'Business' ? 'selected' : '' }}>Business</option>
                                    <option value="Government Employee or other agency" {{ old('client_type', $user->client_type) == 'Government Employee or other agency' ? 'selected' : '' }}>
                                        Government Employee or other agency</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Sex</label>
                            <div class="col-md-12">
                                <select name="sex" class="form-control form-control-line">
                                    <option value="">Select...</option>
                                    <option value="Male" {{ old('sex', $user->sex) == 'Male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="Female" {{ old('sex', $user->sex) == 'Female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Age Bracket</label>
                            <div class="col-md-12">
                                <select name="age_bracket" class="form-control form-control-line">
                                    <option value="">Select...</option>
                                    <option value="19 or lower" {{ old('age_bracket', $user->age_bracket) == '19 or lower' ? 'selected' : '' }}>19 or lower</option>
                                    <option value="20–34" {{ old('age_bracket', $user->age_bracket) == '20–34' ? 'selected' : '' }}>20–34</option>
                                    <option value="35–49" {{ old('age_bracket', $user->age_bracket) == '35–49' ? 'selected' : '' }}>35–49</option>
                                    <option value="50–64" {{ old('age_bracket', $user->age_bracket) == '50–64' ? 'selected' : '' }}>50–64</option>
                                    <option value="65 or higher" {{ old('age_bracket', $user->age_bracket) == '65 or higher' ? 'selected' : '' }}>65 or higher</option>
                                </select>
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