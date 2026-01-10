@extends('layouts.nice-admin')

@section('title', 'Attendance Confirmation')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        @if($status === 'success')
                            <i class="mdi mdi-check-circle text-success" style="font-size: 80px;"></i>
                        @elseif($status === 'info')
                            <i class="mdi mdi-information text-info" style="font-size: 80px;"></i>
                        @else
                            <i class="mdi mdi-alert text-danger" style="font-size: 80px;"></i>
                        @endif
                    </div>

                    <h3 class="font-weight-bold">{{ $message }}</h3>
                    <hr>

                    <div class="text-left mb-4">
                        <p class="mb-1"><strong>Event:</strong> {{ $event->title }}</p>
                        <p class="mb-1"><strong>Participant:</strong> {{ $participant->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $participant->email }}</p>
                        <p class="mb-1"><strong>Organization:</strong> {{ $participant->organization }}</p>
                        <p class="mb-0"><strong>Time:</strong> {{ now()->format('M d, Y h:i A') }}</p>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-info mr-2">
                            <i class="mdi mdi-eye"></i> View Event Details
                        </a>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-view-list"></i> All Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection