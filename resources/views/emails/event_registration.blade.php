<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #273444;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .qr-section {
            text-align: center;
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .details {
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #273444;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Registration Successful!</h2>
            <p>Thank you for registering for <strong>{{ $event->title }}</strong></p>
        </div>

        <div class="details">
            <p>Hi <strong>{{ $participant->name }}</strong>,</p>
            <p>Your registration is confirmed. Here are the event details:</p>
            <ul>
                <li><strong>Venue:</strong> {{ $event->venue_platform }}</li>
                @if($event->dates->count() > 0)
                    <li><strong>Schedule:</strong>
                        <ul>
                            @foreach($event->dates as $date)
                                <li>{{ $date->date->format('M d, Y') }} at
                                    {{ \Carbon\Carbon::parse($date->start_time)->format('h:i A') }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
        </div>

        <div class="qr-section">
            <h3 style="margin-top: 0;">Your Attendance QR Code</h3>
            <p class="small">Present this code at the venue for check-in.</p>
            <img src="{{ $qrCodeUrl }}" alt="Attendance QR Code" style="width: 200px; height: 200px;">
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} MIS Services. All rights reserved.</p>
        </div>
    </div>
</body>

</html>