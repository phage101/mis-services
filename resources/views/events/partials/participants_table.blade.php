@php
    $stdCount = 0;
    foreach ($standardFields as $key => $label) {
        if (in_array($key, $enabledFields) && !in_array($key, ['firstname', 'lastname'])) {
            $stdCount++;
        }
    }
    $totalColumns = 2 + $stdCount + $event->formFields->count();
@endphp

<table id="participants-table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th class="col-name">Name</th>
            @foreach($standardFields as $key => $label)
                @if(in_array($key, $enabledFields) && !in_array($key, ['firstname', 'lastname']))
                    <th class="col-{{ $key }}">{{ $label }}</th>
                @endif
            @endforeach
            @foreach($event->formFields as $field)
                <th class="col-field-{{ $field->id }}">{{ $field->label }}</th>
            @endforeach
            <th class="col-attendance">Days Attended</th>
        </tr>
    </thead>
    <tbody>
        @foreach($event->participants as $participant)
            <tr>
                <td>
                    <strong>{{ $participant->first_name }} {{ $participant->last_name }}</strong>
                    @if(!$participant->first_name && !$participant->last_name)
                        <strong>{{ $participant->name }}</strong>
                    @endif
                </td>

                @foreach($standardFields as $key => $label)
                    @if(in_array($key, $enabledFields) && !in_array($key, ['firstname', 'lastname']))
                        <td>{{ $participant->$key ?: '-' }}</td>
                    @endif
                @endforeach

                @foreach($event->formFields as $field)
                    <td>
                        @php
                            $data = $participant->additional_data[$field->id] ?? null;
                        @endphp
                        @if(is_array($data))
                            {{ implode(', ', $data) }}
                        @else
                            {{ $data ?: '-' }}
                        @endif
                    </td>
                @endforeach

                <td>
                    <span
                        class="badge badge-lg badge-pill {{ $participant->attendances->count() > 0 ? 'badge-success' : 'badge-secondary' }}">
                        {{ $participant->attendances->count() }} / {{ $event->dates->count() }} Days
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>