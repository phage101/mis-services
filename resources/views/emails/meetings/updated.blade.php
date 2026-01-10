@component('mail::message')
# Meeting Request Updated

Hello {{ $meeting->requestor->name }},

Your meeting request **{{ $meeting->request_number ?? '#'.$meeting->id }}** has been updated.

**Topic:** {{ $meeting->topic }}

@if($meeting->description)
**Description:** {{ $meeting->description }}
@endif

@if($meeting->slots->isNotEmpty())
**Requested Slots:**
@foreach($meeting->slots as $slot)
- {{ $slot->meeting_date->format('F d, Y') }} | {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
@endforeach
@endif

**Status:** {{ ucfirst($meeting->status) }}

@if($meeting->platform)
**Platform:** {{ $meeting->platform->name }}
@endif

@if($meeting->host)
**Host:** {{ $meeting->host->name }}
@endif

@if($meeting->meeting_details)
---

**Meeting Details:**

@component('mail::panel')
{!! nl2br(e($meeting->meeting_details)) !!}
@endcomponent
@endif

@component('mail::button', ['url' => route('meetings.show', $meeting->uuid)])
View Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent