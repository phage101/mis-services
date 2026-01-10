<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Sheet - {{ $event->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 11px;
            color: #000;
        }

        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }

            body {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none;
            }

            .page-break {
                page-break-after: always;
            }

            .container {
                margin-top: 0 !important;
                padding: 0 !important;
            }
        }

        .container {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            word-wrap: break-word;
        }

        .header-table {
            margin-bottom: 0;
            border-bottom: none;
        }

        .header-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .logo-section {
            width: 20%;
        }

        .title-section {
            width: 55%;
            text-align: center;
            vertical-align: middle;
        }

        .metadata-section {
            width: 25%;
            font-size: 10px;
            line-height: 1.3;
        }

        .event-info-table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .attendance-table {
            margin-top: -1px;
            /* Align with previous table */
        }

        .attendance-table th {
            text-align: center;
            background-color: #fff;
            font-weight: bold;
            font-size: 10px;
        }

        .index-col {
            width: 3%;
            text-align: center;
        }

        .name-col {
            width: 17%;
        }

        .sex-col {
            width: 6%;
            text-align: center;
        }

        .sex-sub-col {
            width: 3%;
            text-align: center;
            font-size: 9px;
        }

        .company-col {
            width: 14%;
        }

        .designation-col {
            width: 12%;
        }

        .address-col {
            width: 12%;
        }

        .email-col {
            width: 12%;
        }

        .contact-col {
            width: 12%;
        }

        .signature-col {
            width: 12%;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-uppercase: uppercase;
        }

        .btn-print {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        /* Fixed height for rows to ensure consistent layout */
        .attendance-table td {
            height: 28px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: right;">
        <button class="btn-print" onclick="window.print()">Print Attendance Sheet</button>
        <button class="btn-print" style="background: #6c757d;" onclick="window.close()">Close</button>
    </div>

    @php
        $participants = $event->participants;
        $chunkSize = 15;
        $totalParticipants = count($participants);
        $totalPages = max(1, ceil($totalParticipants / $chunkSize));
    @endphp

    @for($page = 0; $page < $totalPages; $page++)
        <div class="container {{ $page < $totalPages - 1 ? 'page-break' : '' }}"
            style="{{ $page > 0 ? 'margin-top: 20px;' : '' }}">
            <!-- Header Table -->
            <table class="header-table">
                <tr>
                    <td class="logo-section" style="padding: 5px; text-align: center;">
                        <img src="{{ asset('img/dti-logo.png') }}" alt="DTI Logo"
                            style="max-height: 50px; max-width: 100%;">
                    </td>
                    <td class="title-section">
                        <h3 style="margin: 0;">ATTENDANCE SHEET</h3>
                        <h4 style="margin: 0;">(INTERNAL)</h4>
                    </td>
                    <td class="metadata-section">
                        Document Code: FM-CT-2<br>
                        Version No.: 1<br>
                        Effectivity Date: February 1, 2024
                    </td>
                </tr>
            </table>

            <!-- Event Info -->
            <table class="event-info-table" style="border-top: none;">
                <tr>
                    <td style="width: 75%; border-top: none;">
                        <strong>TITLE OF EVENT:</strong> {{ $event->title }}
                    </td>
                    <td style="width: 25%; border-top: none;">
                        <strong>DATE :</strong>
                        {{ count($event->dates) > 0 ? $event->dates[0]->date->format('d F Y') : '' }}<br>
                        <strong>VENUE :</strong> {{ $event->venue_platform }}<br>
                        <strong>TIME :</strong>
                        {{ count($event->dates) > 0 ? \Carbon\Carbon::parse($event->dates[0]->start_time)->format('h:i A') : '' }}
                    </td>
                </tr>
            </table>

            <!-- Attendance Table -->
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th rowspan="2" class="index-col">#</th>
                        <th rowspan="2" class="name-col">Name<br><small>(Title, First, Middle, Last, Suffix)</small></th>
                        <th colspan="2" class="sex-col">Sex</th>
                        <th rowspan="2" class="company-col">Company/Office</th>
                        <th rowspan="2" class="designation-col">Designation</th>
                        <th rowspan="2" class="address-col">Address</th>
                        <th rowspan="2" class="email-col">Email Address</th>
                        <th rowspan="2" class="contact-col">Contact No.</th>
                        <th rowspan="2" class="signature-col">Signature</th>
                    </tr>
                    <tr>
                        <th class="sex-sub-col">F</th>
                        <th class="sex-sub-col">M</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $start = $page * $chunkSize;
                        $end = min($start + $chunkSize, $totalParticipants);
                        $currentPageParticipants = $participants->slice($start, $chunkSize);
                        $rowsFilled = count($currentPageParticipants);
                    @endphp

                    @foreach($currentPageParticipants as $index => $participant)
                        <tr>
                            <td class="text-center">{{ $start + $index + 1 }}</td>
                            <td>{{ $participant->name }}</td>
                            <td class="text-center">{{ strtolower($participant->sex) == 'female' ? '/' : '' }}</td>
                            <td class="text-center">{{ strtolower($participant->sex) == 'male' ? '/' : '' }}</td>
                            <td>{{ $participant->organization }}</td>
                            <td>{{ $participant->designation }}</td>
                            <td>{{ $participant->province }}</td>
                            <td>{{ $participant->email }}</td>
                            <td>{{ $participant->contact_no }}</td>
                            <td></td>
                        </tr>
                    @endforeach

                    {{-- Fill remaining rows with blanks to reach 15 per page --}}
                    @for($i = $rowsFilled; $i < $chunkSize; $i++)
                        <tr>
                            <td class="text-center">{{ $start + $i + 1 }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <div style="margin-top: 5px; text-align: right; font-size: 9px;">
                Page {{ $page + 1 }} of {{ $totalPages }}
            </div>
        </div>
    @endfor
</body>

</html>