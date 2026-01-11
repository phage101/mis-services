<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Already Submitted - {{ config('app.name') }}</title>
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <style>
        body {
            background-color: #eef1f6;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .info-card {
            max-width: 500px;
            width: 100%;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 40px;
            text-align: center;
            background: white;
        }

        .icon-box {
            width: 100px;
            height: 100px;
            background: #fff3e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }

        .icon-box i {
            font-size: 50px;
            color: #fb8c00;
        }

        h2 {
            font-weight: 700;
            color: #3e5569;
            margin-bottom: 20px;
        }

        p {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .btn-home {
            background: #3e5569;
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            margin-top: 30px;
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(62, 85, 105, 0.3);
            color: white;
        }
    </style>
</head>

<body>
    <div class="info-card">
        <div class="icon-box">
            <i class="mdi mdi-information-outline"></i>
        </div>
        <h2>Feedback Already Submitted</h2>
        <p>It looks like you have already submitted your feedback for this ticket. Thank you for your review!</p>
        <a href="{{ url('/') }}" class="btn-home">Return to Home</a>
    </div>
</body>

</html>