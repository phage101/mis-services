@extends('layouts.nice-admin')

@section('title', 'User Management')

@push('styles')
    <link href="{{ asset('assets/extra-libs/DataTables/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Users Management</h4>
                        <a class="btn btn-info" href="{{ route('users.create') }}"><i class="mdi mdi-plus"></i> Create New
                            User</a>
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
                                    <th>Roles</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->getRoleNames() as $role)
                                                <span class="badge badge-primary">{{ $role }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            <a class="btn btn-info btn-sm" href="{{ route('users.show', $user->id) }}"><i
                                                    class="mdi mdi-eye"></i></a>
                                            <a class="btn btn-warning btn-sm" href="{{ route('users.edit', $user->id) }}"><i
                                                    class="mdi mdi-pencil"></i></a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                style="display:inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="mdi mdi-delete"></i></button>
                                            </form>
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
            $('#users-table').DataTable();
        });
    </script>
@endpush