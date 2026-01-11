<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Forgot Password - MIS Services</title>
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: #fff;
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="login-card p-4 mx-auto">
                    <div class="text-center mb-4">
                        <img src="{{ asset('img/dti-logo.png') }}" alt="Logo" style="max-height: 60px;" class="mb-3">
                        <h4 class="font-weight-bold text-dark">Reset Password</h4>
                        <p class="text-muted small">Enter your email to receive a reset link</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success small mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" id="reset-request-form">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                        <div class="form-group">
                            <label class="small font-weight-bold">Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="name@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm py-2">
                                Send Password Reset Link
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none small font-weight-bold">
                                <i class="mdi mdi-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $('#reset-request-form').submit(function (e) {
            e.preventDefault();
            var form = this;
            grecaptcha.ready(function () {
                grecaptcha.execute("{{ config('services.recaptcha.site') }}", { action: 'password_reset_request' }).then(function (token) {
                    $('#g-recaptcha-response').val(token);
                    form.submit();
                });
            });
        });
    </script>
</body>

</html>