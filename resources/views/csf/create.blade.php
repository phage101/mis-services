<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Satisfaction Feedback - {{ config('app.name') }}</title>
    <!-- Nice Admin CSS -->
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        body {
            background-color: #eef1f6;
            font-family: 'Poppins', sans-serif;
            color: #3e5569;
        }

        .csf-card {
            max-width: 900px;
            margin: 40px auto;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            background: #fff;
        }

        .csf-header {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }

        .csf-header h2 {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        .csf-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .csf-body {
            padding: 40px;
        }

        .section-title {
            color: #1565c0;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef1f6;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 8px 15px;
            /* Reduced vertical padding */
            height: auto;
            /* flexible height */
            min-height: 45px;
            /* minimum height */
            border: 1px solid #e0e0e0;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            border-color: #1e88e5;
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }

        .rating-group {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .rating-options {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .custom-radio .custom-control-label {
            cursor: pointer;
            padding-left: 5px;
        }

        .signature-pad-wrapper {
            border: 2px dashed #bdbdbd;
            border-radius: 10px;
            padding: 10px;
            background: #fafafa;
            position: relative;
        }

        .signature-pad {
            width: 100%;
            height: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .btn-submit {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            border: none;
            padding: 15px 50px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 5px 20px rgba(21, 101, 192, 0.3);
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(21, 101, 192, 0.4);
        }

        .btn-clear-sig {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        .required-star {
            color: #e53935;
        }

        /* Emoji Rating Styling */
        .emoji-rating {
            display: flex;
            justify-content: space-between;
            max-width: 600px;
            margin: 0 auto;
        }

        .emoji-option {
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s;
            position: relative;
        }

        .emoji-option:hover {
            transform: scale(1.2);
        }

        .emoji-option input[type="radio"] {
            display: none;
        }

        .emoji-label {
            font-size: 2.5rem;
            filter: grayscale(100%);
            opacity: 0.6;
            transition: all 0.3s;
            cursor: pointer;
            display: block;
        }

        .emoji-text {
            font-size: 0.8rem;
            color: #777;
            margin-top: 5px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .emoji-option:hover .emoji-label,
        .emoji-option input[type="radio"]:checked+label {
            filter: grayscale(0%);
            opacity: 1;
            transform: scale(1.1);
        }

        .emoji-option:hover .emoji-text,
        .emoji-option input[type="radio"]:checked+label+.emoji-text {
            opacity: 1;
            color: #1565c0;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site') }}"></script>
</head>

<body>
    <div class="container">
        <div class="card csf-card">
            <div class="csf-header">
                <h2>Client Satisfaction Feedback</h2>
                <p>We value your feedback to help us improve our services.</p>
                @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                @endif
            </div>

            <form action="{{ route('csf.store', $ticket) }}" method="POST" class="csf-body" id="csfForm">
                @csrf

                <!-- A. Client Information -->
                <h4 class="section-title">A. Client Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{ $ticket->requestor->name ?? 'Guest' }}"
                            readonly>
                        <small class="text-muted">Ticket ID: {{ $ticket->request_number ?? $ticket->id }}</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>E-mail Address <span class="required-star">*</span></label>
                        <input type="email" class="form-control" value="{{ $ticket->requestor->email ?? '' }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Client Type</label>
                        <input type="text" class="form-control" value="{{ $ticket->requestor->client_type ?? '' }}"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Sex</label>
                        <input type="text" class="form-control" value="{{ $ticket->requestor->sex ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Age Bracket</label>
                        <input type="text" class="form-control" value="{{ $ticket->requestor->age_bracket ?? '' }}"
                            readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" value="{{ $ticket->requestor->contact_no ?? '' }}"
                            readonly>
                    </div>
                </div>

                <!-- B. Citizen's Charter -->
                <h4 class="section-title mt-4">B. Citizen's Charter Awareness</h4>

                <div class="rating-group">
                    <label class="font-weight-bold">CC1: Are you aware of the Citizen's Charter? <span
                            class="required-star">*</span></label>
                    <select name="cc1_awareness" class="form-control mt-2" required>
                        <option value="">Select...</option>
                        <option value="Yes">Yes, aware before my transaction</option>
                        <option value="No">No, not aware</option>
                    </select>
                </div>

                <div class="rating-group">
                    <label class="font-weight-bold">CC2: Visibility of Citizen's Charter? <span
                            class="required-star">*</span></label>
                    <select name="cc2_visibility" class="form-control mt-2" required>
                        <option value="">Select...</option>
                        <option value="Easy to see">Easy to see</option>
                        <option value="Somewhat easy to see">Somewhat easy to see</option>
                        <option value="Difficult to see">Difficult to see</option>
                        <option value="Not applicable">Not applicable</option>
                    </select>
                </div>

                <div class="rating-group">
                    <label class="font-weight-bold">CC3: Helpfulness of Citizen's Charter? <span
                            class="required-star">*</span></label>
                    <select name="cc3_helpfulness" class="form-control mt-2" required>
                        <option value="">Select...</option>
                        <option value="Helped very much">Helped very much</option>
                        <option value="Helped somewhat">Helped somewhat</option>
                        <option value="Did not help">Did not help</option>
                        <option value="Not applicable">Not applicable</option>
                    </select>
                </div>

                <!-- C. Service Rating -->
                <h4 class="section-title mt-4">C. Service Rating</h4>
                <p class="text-muted small mb-4">Please check the box that corresponds to your rating.</p>

                @php
                    $criteria = [
                        'rating_overall' => 'Overall Rating',
                        'rating_responsiveness' => 'Responsiveness',
                        'rating_reliability' => 'Reliability',
                        'rating_access_facilities' => 'Access and Facilities',
                        'rating_communication' => 'Communication',
                        'rating_costs' => 'Cost (if applicable)',
                        'rating_integrity' => 'Integrity',
                        'rating_assurance' => 'Assurance',
                        'rating_outcome' => 'Outcome',
                        'rating_resource_speaker' => 'Resource Speaker'
                    ];
                @endphp

                @foreach($criteria as $field => $label)
                    <div class="rating-group text-center">
                        <label class="font-weight-bold text-dark d-block mb-3" style="font-size: 1.1rem;">{{ $label }} <span
                                class="required-star">*</span></label>
                        <div class="emoji-rating">
                            <!-- 5 - Very Happy -->
                            <div class="emoji-option">
                                <input type="radio" name="{{ $field }}" id="{{ $field }}_5" value="5" required>
                                <label for="{{ $field }}_5" class="emoji-label">😍</label>
                                <div class="emoji-text">Very Happy</div>
                            </div>

                            <!-- 4 - Happy -->
                            <div class="emoji-option">
                                <input type="radio" name="{{ $field }}" id="{{ $field }}_4" value="4">
                                <label for="{{ $field }}_4" class="emoji-label">🙂</label>
                                <div class="emoji-text">Happy</div>
                            </div>

                            <!-- 3 - Neutral -->
                            <div class="emoji-option">
                                <input type="radio" name="{{ $field }}" id="{{ $field }}_3" value="3">
                                <label for="{{ $field }}_3" class="emoji-label">😐</label>
                                <div class="emoji-text">Neutral</div>
                            </div>

                            <!-- 2 - Sad -->
                            <div class="emoji-option">
                                <input type="radio" name="{{ $field }}" id="{{ $field }}_2" value="2">
                                <label for="{{ $field }}_2" class="emoji-label">🙁</label>
                                <div class="emoji-text">Sad</div>
                            </div>

                            <!-- 1 - Very Angry -->
                            <div class="emoji-option">
                                <input type="radio" name="{{ $field }}" id="{{ $field }}_1" value="1">
                                <label for="{{ $field }}_1" class="emoji-label">😠</label>
                                <div class="emoji-text">Very Angry</div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- D. Comments -->
                <h4 class="section-title mt-4">D. Comments & Suggestions</h4>

                <div class="form-group mb-4">
                    <label>Reason/s for your "NEITHER", "DISAGREE" or "STRONGLY DISAGREE" answer</label>
                    <textarea name="rating_remarks" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label>Other Comments / Suggestions</label>
                    <textarea name="comments" class="form-control" rows="3"
                        placeholder="How can we improve our service?"></textarea>
                </div>

                <!-- E. Signature -->
                <h4 class="section-title mt-4">E. Consent and Signature</h4>

                <div class="signature-pad-wrapper mb-4">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-clear-sig"
                        onclick="signaturePad.clear()">Clear</button>
                    <canvas class="signature-pad" id="signatureCanvas"></canvas>
                    <p class="text-center text-muted small mt-2">Please sign in the box above</p>
                    <input type="hidden" name="signature" id="signatureInput" required>
                </div>

                <div class="text-center mt-5">
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    <button type="submit" class="btn btn-submit text-white px-5 py-3">
                        <i class="mdi mdi-check-circle-outline mr-2"></i> Submit Feedback
                    </button>
                </div>

            </form>
        </div>

        <div class="text-center pb-5 text-muted small">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>

    <script>
        var canvas = document.getElementById('signatureCanvas');

        function resizeCanvas() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        document.getElementById('csfForm').addEventListener('submit', function (e) {
            e.preventDefault();
            var form = this;

            if (!signaturePad.isEmpty()) {
                var data = signaturePad.toDataURL('image/png');
                document.getElementById('signatureInput').value = data;
            }

            grecaptcha.ready(function () {
                grecaptcha.execute("{{ config('services.recaptcha.site') }}", { action: 'submit_csf' }).then(function (token) {
                    var recaptchaInput = document.getElementById('g-recaptcha-response');
                    if (recaptchaInput) {
                        recaptchaInput.value = token;
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>