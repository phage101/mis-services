@extends('layouts.nice-admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-cyan text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-account-multiple"></i></h1>
                    <h6 class="text-white">Total Users</h6>
                    <h3 class="text-white">1,248</h3>
                </div>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-success text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-account-check"></i></h1>
                    <h6 class="text-white">Active Sessions</h6>
                    <h3 class="text-white">86</h3>
                </div>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-security"></i></h1>
                    <h6 class="text-white">My Role</h6>
                    <h3 class="text-white" style="text-transform: capitalize;">{{ Auth::user()->getRoleNames()->first() }}
                    </h3>
                </div>
            </div>
        </div>
        <!-- Card 4 -->
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover">
                <div class="box bg-danger text-center">
                    <h1 class="font-light text-white"><i class="mdi mdi-bell-ring"></i></h1>
                    <h6 class="text-white">Notifications</h6>
                    <h3 class="text-white">12</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Activity</h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Login Success</td>
                                    <td>Just now</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Update Profile</td>
                                    <td>5 mins ago</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td>Admin User</td>
                                    <td>System Backup</td>
                                    <td>1 hour ago</td>
                                    <td><span class="badge badge-info">Processing</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection