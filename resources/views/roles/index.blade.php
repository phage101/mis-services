@extends('layouts.nice-admin')

@section('title', 'Role Management')

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-info text-white"><i
                                    class="ti-package"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total Roles</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['total'] }}</h3>
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
                        <h4 class="card-title mb-0">Role Management</h4>
                        @can('roles.create')
                            <a class="btn btn-info" href="{{ route('roles.create') }}"><i class="mdi mdi-plus"></i> Create New
                                Role</a>
                        @endcan
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">{{ $message }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="roles-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            @can('roles.list')
                                                <a class="btn btn-info btn-sm" href="{{ route('roles.show', $role) }}"><i
                                                        class="mdi mdi-eye"></i></a>
                                            @endcan
                                            @can('roles.edit')
                                                <a class="btn btn-warning btn-sm" href="{{ route('roles.edit', $role) }}"><i
                                                        class="mdi mdi-pencil"></i></a>
                                            @endcan
                                            @can('roles.delete')
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST"
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
            $('#roles-table').DataTable({
                "paging": true,
                "info": true
            });
        });
    </script>
@endpush