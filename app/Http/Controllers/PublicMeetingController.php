<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingSlot;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetingCreated;

class PublicMeetingController extends Controller
{
    /**
     * Show the public meeting request form.
     */
    public function index()
    {
        $offices = Office::orderBy('name')->get();
        return view('meetings.public', compact('offices'));
    }

    /**
     * Show/Handle meeting tracking.
     */
    public function track(Request $request)
    {
        $search = $request->get('search');
        $meetings = null;

        if ($search) {
            $query = Meeting::query()->with(['requestor', 'platform', 'host', 'slots']);

            if (filter_var($search, FILTER_VALIDATE_EMAIL)) {
                $query->whereHas('requestor', function ($q) use ($search) {
                    $q->where('email', $search);
                });
            } else {
                $query->where('request_number', $search);
            }

            $meetings = $query->orderBy('created_at', 'desc')->get();
        }

        return view('meetings.track', compact('meetings', 'search'));
    }

    /**
     * Store a newly created meeting from the public form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'requestor_name' => 'required|string|max:255',
            'requestor_id' => 'nullable|exists:users,id',
            'email' => 'required_without:requestor_id|nullable|email|unique:users,email',
            'office_id' => 'required_without:requestor_id|nullable|exists:offices,id',
            'division_id' => 'required_without:requestor_id|nullable|exists:divisions,id',
            'topic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slots' => 'required|array|min:1',
            'slots.*.date' => 'required|date',
            'slots.*.start' => 'required',
            'slots.*.end' => 'required',
            'g-recaptcha-response' => ['required', new \App\Rules\Recaptcha],
        ]);

        $requestorId = $request->requestor_id;
        $requestorName = $request->requestor_name;

        // Create user if they don't exist
        if (!$requestorId) {
            $password = Str::random(10);
            $user = User::create([
                'name' => $requestorName,
                'email' => $request->email,
                'password' => Hash::make($password),
                'office_id' => $request->office_id,
                'division_id' => $request->division_id,
            ]);

            $user->assignRole('User');
            $requestorId = $user->id;
        }

        $meeting = Meeting::create([
            'date_requested' => now(),
            'requestor_id' => $requestorId,
            'topic' => $request->topic,
            'description' => $request->description,
            'status' => Meeting::STATUS_PENDING,
        ]);

        foreach ($request->slots as $slotData) {
            MeetingSlot::create([
                'meeting_id' => $meeting->id,
                'meeting_date' => $slotData['date'],
                'start_time' => $slotData['start'],
                'end_time' => $slotData['end'],
            ]);
        }

        // Send email to requestor
        try {
            Mail::to($meeting->requestor->email)->send(new MeetingCreated($meeting));
        } catch (\Exception $e) {
            // Silence mail errors
        }

        return redirect()->route('public.meetings.track', ['search' => $meeting->request_number])
            ->with('success', 'Your meeting request has been submitted successfully (Request #' . $meeting->request_number . '). We will review and contact you shortly.');
    }
}
