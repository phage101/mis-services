@component('mail::message')
# Meeting Request Created

Hello {{ $meeting->requestor->name }},

Your meeting request **{{ $meeting->request_number ?? '#' . $meeting->id }}** has been successfully submitted.

<table style="width: 100%; border: none; border-collapse: collapse;">
    <tr>
        <td style="width: 30%; font-weight: bold; padding: 5px 0;">Topic:</td>
        <td style="padding: 5px 0;">{{ $meeting->topic }}</td>
    </tr>
    @if($meeting->description)
        <tr>
            <td style="font-weight: bold; padding: 5px 0;">Description:</td>
            <td style="padding: 5px 0;">{{ $meeting->description }}</td>
        </tr>
    @endif
    @if($meeting->slots->isNotEmpty())
        <tr>
            <td style="font-weight: bold; padding: 5px 0; vertical-align: top;">Requested Slots:</td>
            <td style="padding: 5px 0;">
                @foreach($meeting->slots as $slot)
                    {{ $slot->meeting_date->format('F d, Y') }} |
                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}<br>
                @endforeach
            </td>
        </tr>
    @endif
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Status:</td>
        <td style="padding: 5px 0;">{{ ucfirst($meeting->status) }}</td>
    </tr>
</table>

You can track the status of your request by clicking the button below.

@component('mail::button', ['url' => route('meetings.show', $meeting->uuid)])
View Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent