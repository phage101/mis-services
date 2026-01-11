@component('mail::message')
# Ticket Updated

Hello {{ $ticket->requestor->name }},

Your ticket **{{ $ticket->request_number ?? '#' . $ticket->id }}** has been updated.

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
        <td style="font-weight: bold; padding: 5px 0;">Status:</td>
        <td style="padding: 5px 0;">{{ ucfirst($ticket->status) }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Urgency:</td>
        <td style="padding: 5px 0;">{{ ucfirst($ticket->urgency) }}</td>
    </tr>
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Date Requested:</td>
        <td style="padding: 5px 0;">{{ $ticket->date_requested->format('F d, Y') }}</td>
    </tr>
    @if($ticket->datetime_started)
        <tr>
            <td style="font-weight: bold; padding: 5px 0;">Started:</td>
            <td style="padding: 5px 0;">{{ $ticket->datetime_started->format('Y-m-d H:i') }}</td>
        </tr>
    @endif
    @if($ticket->datetime_ended)
        <tr>
            <td style="font-weight: bold; padding: 5px 0;">Ended:</td>
            <td style="padding: 5px 0;">{{ $ticket->datetime_ended->format('Y-m-d H:i') }}</td>
        </tr>
    @endif
    @if($ticket->channel)
        <tr>
            <td style="font-weight: bold; padding: 5px 0;">Channel:</td>
            <td style="padding: 5px 0;">{{ $ticket->channel }}</td>
        </tr>
    @endif
    <tr>
        <td style="font-weight: bold; padding: 5px 0;">Remarks:</td>
        <td style="padding: 5px 0;">{{ $ticket->remarks ?? 'No remarks provided.' }}</td>
    </tr>
    @if($ticket->responses->isNotEmpty())
        <tr>
            <td style="font-weight: bold; padding: 5px 0; vertical-align: top;">Action Taken:</td>
            <td style="padding: 5px 0;">
                {{ $ticket->responses->last()->action_taken }}<br>
                <small style="color: #6c757d;"><em>- by {{ $ticket->responses->last()->user->name }}</em></small>
            </td>
        </tr>
    @endif
</table>

<br>

<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td style="padding: 10px;">
                                    <a href="{{ route('public.tickets.track', ['search' => $ticket->request_number]) }}"
                                        class="button button-primary" target="_blank" rel="noopener"
                                        style="display: inline-block; padding: 10px 20px; color: #ffffff; background-color: #3490dc; border-radius: 4px; text-decoration: none;">View
                                        Ticket</a>
                                </td>
                                @if($ticket->status == 'completed')
                                    <td style="padding: 10px;">
                                        <a href="{{ route('csf.create', $ticket->uuid) }}" class="button button-success"
                                            target="_blank" rel="noopener"
                                            style="display: inline-block; padding: 10px 20px; color: #ffffff; background-color: #38c172; border-radius: 4px; text-decoration: none;">Give
                                            Feedback</a>
                                    </td>
                                @endif
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

Thanks,<br>
{{ config('app.name') }}
@endcomponent