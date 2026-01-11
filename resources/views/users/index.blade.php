@extends('layouts.nice-admin')

@section('title', 'User Management')

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-md-4">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-danger text-white"><i
                                    class="ti-user"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total Users</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-4">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-info text-white"><i
                                    class="ti-shield"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Admins</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['admins'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-4">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-success text-white"><i
                                    class="ti-user"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Regular Users</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['users'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Users Management</h4>
                        @can('users.create')
                            <a class="btn btn-info" href="{{ route('users.create') }}"><i class="mdi mdi-plus"></i> Create New
                                User</a>
                        @endcan
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="users-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Office</th>
                                    <th>Division</th>
                                    <th>Roles</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->office->name ?? 'N/A' }}</td>
                                        <td>{{ $user->division->name ?? 'N/A' }}</td>
                                        <td>
                                            @foreach($user->getRoleNames() as $role)
                                                <span class="badge badge-primary">{{ $role }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            @can('users.list')
                                                <a class="btn btn-info btn-sm" href="{{ route('users.show', $user) }}"><i
                                                        class="mdi mdi-eye"></i></a>
                                            @endcan
                                            @can('users.edit')
                                                <a class="btn btn-warning btn-sm" href="{{ route('users.edit', $user) }}"><i
                                                        class="mdi mdi-pencil"></i></a>
                                            @endcan
                                            @can('users.delete')
                                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                    style="display:inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="mdi mdi-delete"></i></button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#users-table').DataTable({
                "paging": true,
                "info": true
            });
        });
    </script>
@endpush