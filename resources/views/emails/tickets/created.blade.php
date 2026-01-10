@component('mail::message')
# Ticket Created

Hello {{ $ticket->requestor->name }},

Your ticket **{{ $ticket->request_number ?? '#' . $ticket->id }}** has been successfully created.

<table style="width: 100%; border: none; border-collapse: collapse;">
    <tr>
        <td style="width: 30%; font-weight: bold; padding: 5px 0;">Subject:</td>
        <td style="padding: 5px 0;">{{ $ticket->complaint }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Request Type:</td>
        <td style="padding: 5px 0;">{{ $ticket->requestType->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Category:</td>
        <td style="padding: 5px 0;">{{ $ticket->category->name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Urgency:</td>
        <td style="padding: 5px 0;">{{ ucfirst($ticket->urgency) }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Date Requested:</td>
        <td style="padding: 5px 0;">{{ $ticket->date_requested->format('F d, Y') }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Status:</td>
        <td style="padding: 5px 0;">{{ ucfirst($ticket->status) }}</td>
    </tr>
</table>

You can track the status of your ticket by clicking the button below.

@component('mail::button', ['url' => route('tickets.show', $ticket->uuid)])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent