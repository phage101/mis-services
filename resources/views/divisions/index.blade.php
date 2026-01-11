@extends('layouts.nice-admin')

@section('title', 'Division Management')

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
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-info text-white"><i
                                    class="ti-layout-grid2"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total Divisions</h6>
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
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-success text-white"><i
                                    class="ti-user"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">With Users</h6>
                            <h3 class="font-medium mb-0">{{ $kpis['with_users'] }}</h3>
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
                        <div class="m-r-10"><span class="btn btn-circle btn-lg bg-danger text-white"><i
                                    class="ti-id-badge"></i></span></div>
                        <div>
                            <h6 class="card-subtitle text-muted">Total Active Users</h6>
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
                        <h4 class="card-title mb-0">Divisions List</h4>
                        @can('divisions.create')
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createDivisionModal">
                                <i class="mdi mdi-plus"></i> Create New Division
                            </button>
                        @endcan
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="divisions-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Office</th>
                                    <th>Users</th>
                                    <th class="text-right" style="width:1%; white-space:nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($divisions as $division)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $division->name }}</td>
                                        <td>{{ $division->code ?? '-' }}</td>
                                        <td>{{ $division->office->name ?? 'N/A' }}</td>
                                        <td><span class="badge badge-success">{{ $division->users_count }}</span></td>
                                        <td class="text-right" style="white-space:nowrap;">
                                            @can('divisions.edit')
                                                <button class="btn btn-warning btn-sm edit-division" data-id="{{ $division->uuid }}"
                                                    data-name="{{ $division->name }}" data-code="{{ $division->code }}"
                                                    data-office-id="{{ $division->office_id }}" data-toggle="modal"
                                                    data-target="#editDivisionModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                            @endcan
                                            @can('divisions.delete')
                                                <form action="{{ route('divisions.destroy', $division) }}" method="POST"
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
    <div class="modal fade" id="createDivisionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('divisions.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Division</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Office</label>
                            <select name="office_id" class="form-control" required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Division Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter division name" required>
                        </div>
                        <div class="form-group">
                            <label>Code (Optional)</label>
                            <input type="text" name="code" class="form-control" placeholder="Enter division code">
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
    <div class="modal fade" id="editDivisionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editDivisionForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Division</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Office</label>
                            <select name="office_id" id="edit_office_id" class="form-control" required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Division Name</label>
                            <input type="text" name="name" id="edit_div_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Code (Optional)</label>
                            <input type="text" name="code" id="edit_div_code" class="form-control">
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
            $('#divisions-table').DataTable({
                "paging": true,
                "info": true
            });

            $('.edit-division').click(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var code = $(this).data('code');
                var officeId = $(this).data('office-id');

                $('#edit_div_name').val(name);
                $('#edit_div_code').val(code);
                $('#edit_office_id').val(officeId);
                $('#editDivisionForm').attr('action', '{{ route("divisions.update", ":id") }}'.replace(':id', id));
            });
        });
    </script>
@endpush