<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationConfirmation;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\EventFormField;
use App\Models\EventParticipant;
use App\Models\EventSpeaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Event::class);
        $events = Event::with(['organizer'])->orderBy('start_date', 'desc')->get();

        $kpis = [
            'total' => Event::count(),
            'upcoming' => Event::where('status', 'upcoming')->count(),
            'ongoing' => Event::where('status', 'ongoing')->count(),
            'completed' => Event::where('status', 'completed')->count(),
        ];

        return view('events.index', compact('events', 'kpis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Event::class);
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Event::class);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|string',
            'venue_type' => 'required|in:onsite,online,hybrid',
            'venue_platform' => 'required|string',
            'expected_participants' => 'nullable|integer',
            'registration_fields' => 'nullable|array',

            'form_fields' => 'nullable|array',
            'form_fields.*.label' => 'required_with:form_fields|string',
            'form_fields.*.field_type' => 'required_with:form_fields|in:text,textarea,select,checkbox,radio',

            'dates' => 'required|array|min:1',
            'dates.*.date' => 'required|date',
            'dates.*.start_time' => 'required',
            'dates.*.end_time' => 'required',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Determine main start/end dates for the event record
        $dates = collect($request->dates);
        $minDate = $dates->min('date');
        $maxDate = $dates->max('date');
        $firstDateStart = $dates->where('date', $minDate)->min('start_time');
        $lastDateEnd = $dates->where('date', $maxDate)->max('end_time');

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_type' => $request->event_type,
            'classification' => $request->classification,
            'venue_type' => $request->venue_type,
            'venue_platform' => $request->venue_platform,
            'start_date' => $minDate,
            'end_date' => $maxDate,
            'start_time' => $firstDateStart,
            'end_time' => $lastDateEnd,
            'status' => 'upcoming',
            'expected_participants' => $request->expected_participants ?? 0,
            'registration_fields' => $request->registration_fields ?? [],
            'enable_qr' => $request->boolean('enable_qr'),
            'organizer_id' => Auth::id(),
            'banner_image' => $request->hasFile('banner_image')
                ? $request->file('banner_image')->store('event-banners', 'public')
                : null,
        ]);

        foreach ($request->dates as $dateData) {
            $event->dates()->create($dateData);
        }

        if ($request->has('form_fields')) {
            foreach ($request->form_fields as $index => $fieldData) {
                if (!empty($fieldData['label'])) {
                    $event->formFields()->create([
                        'label' => $fieldData['label'],
                        'field_type' => $fieldData['field_type'],
                        'options' => !empty($fieldData['options']) ? array_map('trim', explode(',', $fieldData['options'])) : null,
                        'is_required' => isset($fieldData['is_required']),
                        'order' => $index
                    ]);
                }
            }
        }

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event)
    {
        $event->load(['formFields', 'participants', 'organizer', 'dates']);

        if ($request->ajax()) {
            $standardFields = [
                'firstname' => 'First Name',
                'lastname' => 'Last Name',
                'organization' => 'Organization (Business/School/etc)',
                'designation' => 'Designation/Position',
                'age_bracket' => 'Age Bracket',
                'sex' => 'Sex',
                'province' => 'Province',
                'contact_no' => 'Contact No.',
                'email' => 'Email Address'
            ];
            $enabledFields = $event->registration_fields ?? [];

            return view('events.partials.participants_table', compact('event', 'standardFields', 'enabledFields'))->render();
        }

        return view('events.show', compact('event'));
    }

    /**
     * Update attendance status via AJAX.
     */
    public function updateAttendance(Request $request, Event $event)
    {
        $request->validate([
            'participant_id' => 'required|exists:event_participants,id',
            'status' => 'required|in:registered,present,absent',
        ]);

        $participant = $event->participants()->findOrFail($request->participant_id);
        $participant->update(['attendance_status' => $request->status]);

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $event->load(['formFields', 'participants', 'dates']);
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|string',
            'venue_type' => 'required|in:onsite,online,hybrid',
            'venue_platform' => 'required|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'expected_participants' => 'nullable|integer',
            'registration_fields' => 'nullable|array',

            'form_fields' => 'nullable|array',
            'form_fields.*.label' => 'nullable|string',

            'dates' => 'required|array|min:1',
            'dates.*.date' => 'required|date',
            'dates.*.start_time' => 'required',
            'dates.*.end_time' => 'required',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Determine main start/end dates for the event record
        $dates = collect($request->dates);
        $minDate = $dates->min('date');
        $maxDate = $dates->max('date');
        $firstDateStart = $dates->where('date', $minDate)->min('start_time');
        $lastDateEnd = $dates->where('date', $maxDate)->max('end_time');

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'event_type' => $request->event_type,
            'classification' => $request->classification,
            'venue_type' => $request->venue_type,
            'venue_platform' => $request->venue_platform,
            'start_date' => $minDate,
            'end_date' => $maxDate,
            'start_time' => $firstDateStart,
            'end_time' => $lastDateEnd,
            'status' => $request->status,
            'expected_participants' => $request->expected_participants ?? 0,
            'registration_fields' => $request->registration_fields ?? [],
            'enable_qr' => $request->boolean('enable_qr'),
        ]);

        if ($request->hasFile('banner_image')) {
            // Delete old banner if exists
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $event->update([
                'banner_image' => $request->file('banner_image')->store('event-banners', 'public')
            ]);
        }

        $event->dates()->delete();
        foreach ($request->dates as $dateData) {
            $event->dates()->create($dateData);
        }

        if ($request->has('form_fields')) {
            $event->formFields()->delete();
            foreach ($request->form_fields as $index => $fieldData) {
                if (!empty($fieldData['label'])) {
                    $event->formFields()->create([
                        'label' => $fieldData['label'],
                        'field_type' => $fieldData['field_type'],
                        'options' => !empty($fieldData['options'])
                            ? (is_array($fieldData['options'])
                                ? array_map('trim', $fieldData['options'])
                                : array_map('trim', explode(',', $fieldData['options'])))
                            : null,
                        'is_required' => isset($fieldData['is_required']),
                        'order' => $index
                    ]);
                }
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Public registration page.
     */
    public function registrationPage(Event $event)
    {
        if ($event->status === 'cancelled') {
            abort(403, 'This event registration is closed.');
        }
        $event->load('formFields');
        return view('events.register', compact('event'));
    }

    /**
     * Submit registration.
     */
    public function register(Request $request, Event $event)
    {
        $registrationFields = $event->registration_fields ?? [];

        $validationRules = [];

        // Standard Fields Validation
        if (in_array('firstname', $registrationFields))
            $validationRules['first_name'] = 'required|string|max:255';
        if (in_array('lastname', $registrationFields))
            $validationRules['last_name'] = 'required|string|max:255';
        if (in_array('email', $registrationFields))
            $validationRules['email'] = 'required|email|max:255';
        if (in_array('contact_no', $registrationFields))
            $validationRules['contact_no'] = 'required|string|max:20';
        if (in_array('organization', $registrationFields))
            $validationRules['organization'] = 'required|string|max:255';
        if (in_array('designation', $registrationFields))
            $validationRules['designation'] = 'required|string|max:255';
        if (in_array('province', $registrationFields))
            $validationRules['province'] = 'required|string|max:255';
        if (in_array('sex', $registrationFields))
            $validationRules['sex'] = 'required|string';
        if (in_array('age_bracket', $registrationFields))
            $validationRules['age_bracket'] = 'required|string';

        // Custom Fields Validation
        foreach ($event->formFields as $field) {
            $rule = $field->is_required ? ['required'] : ['nullable'];
            $validationRules['custom_fields.' . $field->id] = $rule;
        }

        $validationRules['privacy_consent'] = 'required|accepted';

        $request->validate($validationRules);

        // Check for duplicate email if provided
        if ($request->email && $event->participants()->where('email', $request->email)->exists()) {
            return redirect()->back()->withErrors(['email' => 'This email address is already registered for this event.'])->withInput();
        }

        // Check for duplicate name if both first and last name are provided
        if ($request->first_name && $request->last_name) {
            $nameExists = $event->participants()
                ->where('first_name', $request->first_name)
                ->where('last_name', $request->last_name)
                ->exists();

            if ($nameExists) {
                return redirect()->back()->withErrors(['first_name' => 'A participant with this name is already registered for this event.'])->withInput();
            }
        }

        $province = $request->province;
        if ($province == 'Others') {
            $province = '';
        }

        $participant = $event->participants()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => trim($request->first_name . ' ' . $request->last_name) ?: 'Participant',
            'organization' => $request->organization,
            'designation' => $request->designation,
            'age_bracket' => $request->age_bracket,
            'sex' => $request->sex,
            'province' => $province,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'type' => $request->type ?? 'external',
            'organization_msme' => $request->organization ?? $request->organization_msme,
            'attendance_status' => 'registered',
            'additional_data' => $request->custom_fields ?? [],
        ]);

        // Send confirmation email if email is provided AND enable_qr is active
        if ($participant->email && $event->enable_qr) {
            try {
                Mail::to($participant->email)->send(new EventRegistrationConfirmation($event, $participant));
            } catch (\Exception $e) {
                // Log or ignore mail errors to not block registration success
                \Log::error('Registration Email Error: ' . $e->getMessage());
            }
        }

        $response = redirect()->back()->with('success', 'Registration submitted successfully!');

        // Only flash participant details for QR display if enable_qr is active
        if ($event->enable_qr) {
            $response->with('participant_uuid', $participant->uuid)
                ->with('participant_name', $participant->name);
        }

        return $response;
    }

    /**
     * Mark attendance from QR code.
     */
    public function markAttendance(Request $request, Event $event, $participantUuid)
    {
        $this->authorize('manageAttendance', $event);
        $participant = $event->participants()->where('uuid', $participantUuid)->firstOrFail();

        // Find the date for which we are marking attendance
        $dateId = $request->event_date_id;

        if (!$dateId) {
            // Default to today's date if matches any event date
            $today = now()->format('Y-m-d');
            $eventDate = $event->dates()->whereDate('date', $today)->first();
            if (!$eventDate) {
                // If not today, default to the first date as fallback or error
                $eventDate = $event->dates()->first();
            }
            $dateId = $eventDate->id ?? null;
        }

        if (!$dateId) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No valid event date found for this attendance.',
                ], 400);
            }
            abort(400, 'No valid event date found.');
        }

        $eventDate = $event->dates()->findOrFail($dateId);

        // Check if already marked for THIS specific date
        $attendance = $participant->attendances()->where('event_date_id', $dateId)->first();

        if ($attendance) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Attendance already marked for ' . $participant->name . ' on ' . $eventDate->date->format('M d'),
                    'participant' => $participant
                ]);
            }
            return view('events.attendance_confirm', [
                'event' => $event,
                'participant' => $participant,
                'message' => 'Attendance already marked for this date.',
                'status' => 'info'
            ]);
        }

        // Record the attendance
        $participant->attendances()->create([
            'event_date_id' => $dateId,
            'status' => 'present',
            'scanned_at' => now()
        ]);

        // Keep legacy backward compatibility for single-date logic if needed
        $participant->update(['attendance_status' => 'present']);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Attendance marked successfully for ' . $participant->name,
                'participant' => $participant
            ]);
        }

        return view('events.attendance_confirm', [
            'event' => $event,
            'participant' => $participant,
            'message' => 'Attendance marked successfully!',
            'status' => 'success'
        ]);
    }

    /**
     * Display simple reports.
     */
    public function reports(Request $request)
    {
        $query = Event::withCount([
            'participants',
            'participants as present_count' => function ($q) {
                $q->where('attendance_status', 'present');
            }
        ])->orderBy('start_date', 'desc');

        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('start_date', '<=', $request->end_date);
        }
        if ($request->event_id) {
            $query->where('uuid', $request->event_id);
        }

        $events = $query->get();
        return view('events.reports', compact('events'));
    }

    /**
     * Print attendance sheet based on template.
     */
    public function printAttendance(Event $event)
    {
        $event->load(['participants', 'dates']);
        return view('events.print_attendance', compact('event'));
    }
}
