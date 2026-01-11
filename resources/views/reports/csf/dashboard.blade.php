@extends('layouts.nice-admin')

@section('title', 'CSF Dashboard')

@section('content')
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h4 class="card-title text-white">Total Responses</h4>
                    <div class="d-flex align-items-center">
                        <span class="display-4"><i class="mdi mdi-comment-multiple-outline"></i></span>
                        <div class="ml-auto">
                            <h2 class="font-weight-medium text-white mb-0">{{ number_format($totalResponses) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="card-title text-white">Average Overall Rating</h4>
                    <div class="d-flex align-items-center">
                        <span class="display-4"><i class="mdi mdi-star"></i></span>
                        <div class="ml-auto">
                            <h2 class="font-weight-medium text-white mb-0">{{ number_format($averageRating, 1) }} / 5.0</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="card-title text-white">Net Satisfaction Rating</h4>
                    <div class="d-flex align-items-center">
                        <span class="display-4"><i class="mdi mdi-thumb-up"></i></span>
                        <div class="ml-auto">
                            <!-- Placeholder logic for Net Satisfaction Rating (can be % of 4s and 5s) -->
                            <h2 class="font-weight-medium text-white mb-0">N/A</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Criteria Breakdown -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Rating Breakdown by Criteria (Avg)</h4>
                    <div class="table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    <th class="text-right">Average Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criteriaAverages as $criteria => $avg)
                                    <tr>
                                        <td>{{ $criteria }}</td>
                                        <td class="text-right">
                                            @if($avg)
                                                <span class="badge badge-pill badge-info"
                                                    style="font-size: 1rem;">{{ number_format($avg, 1) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Type breakdown -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Responses by Client Type</h4>
                    <div class="table-responsive mt-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client Type</th>
                                    <th class="text-right">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clientTypes as $type => $count)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td class="text-right">{{ $count }}</td>
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