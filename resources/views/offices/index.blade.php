@extends('layouts.nice-admin')

@section('title', 'Office Management')

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
                                    class="ti-home"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total Offices</h6>
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
                                    class="ti-layers"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">With Divisions</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['with_divisions'] }}</h3>
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
                            <h6 class="card-subtitle text-muted">Assigned Users</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['total_users'] }}</h3>
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
                        <h4 class="card-title mb-0">Offices List</h4>
                        @can('offices.create')
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createOfficeModal">
                                <i class="mdi mdi-plus"></i> Create New Office
                            </button>
                        @endcan
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="offices-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Divisions</th>
                                    <th>Users</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offices as $office)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $office->name }}</td>
                                        <td>{{ $office->code ?? '-' }}</td>
                                        <td><span class="badge badge-info">{{ $office->divisions_count }}</span></td>
                                        <td><span class="badge badge-success">{{ $office->users_count }}</span></td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            @can('offices.edit')
                                                <button class="btn btn-warning btn-sm edit-office" data-id="{{ $office->id }}"
                                                    data-name="{{ $office->name }}" data-code="{{ $office->code }}"
                                                    data-toggle="modal" data-target="#editOfficeModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                            @endcan
                                            @can('offices.delete')
                                                <form action="{{ route('offices.destroy', $office->id) }}" method="POST"
                                                    style="display:inline;" onsubmit="return confirm('Are you sure?')">
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

    <!-- Create Modal -->
    <div class="modal fade" id="createOfficeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('offices.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Office</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Office Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter office name" required>
                        </div>
                        <div class="form-group">
                            <label>Code (Optional)</label>
                            <input type="text" name="code" class="form-control" placeholder="Enter office code">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editOfficeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editOfficeForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Office</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Office Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Code (Optional)</label>
                            <input type="text" name="code" id="edit_code" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extra-libs/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#offices-table').DataTable({
                "paging": true,
                "info": true
            });

            $('.edit-office').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var code = $(this).data('code');

                $('#edit_name').val(name);
                $('#edit_code').val(code);
                $('#editOfficeForm').attr('action', '{{ route("offices.update", ":id") }}'.replace(':id', id));
            });
        });
    </script>
@endpush