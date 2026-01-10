<?php

namespace App\Http\Controllers;

use App\Models\Host;
use App\Models\Meeting;
use App\Models\MeetingSlot;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MeetingCreated;
use App\Mail\MeetingUpdated;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Meeting::class);
        $user = Auth::user();
        $query = Meeting::query();

        if (!$user->hasRole('Admin')) {
            $query->where('requestor_id', $user->id);
        }

        $meetings = (clone $query)->with(['requestor', 'platform', 'host', 'slots'])->orderBy('created_at', 'desc')->get();

        $kpis = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', Meeting::STATUS_PENDING)->count(),
            'scheduled' => (clone $query)->where('status', Meeting::STATUS_SCHEDULED)->count(),
            'conflict' => (clone $query)->where('status', Meeting::STATUS_CONFLICT)->count(),
        ];

        return view('meetings.index', compact('meetings', 'kpis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Meeting::class);
        $users = [];
        if (Auth::user()->hasRole('Admin')) {
            $users = User::orderBy('name')->get();
        }
        return view('meetings.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Meeting::class);
        $request->validate([
            'topic' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_requested' => 'required|date',
            'slots' => 'required|array|min:1',
            'slots.*.date' => 'required|date',
            'slots.*.start' => 'required',
            'slots.*.end' => 'required',
        ]);

        if (Auth::user()->hasRole('Admin')) {
            $request->validate(['requestor_id' => 'required|exists:users,id']);
        }

        $meeting = Meeting::create([
            'topic' => $request->topic,
            'description' => $request->description,
            'date_requested' => $request->date_requested,
            'requestor_id' => Auth::user()->hasRole('Admin') ? $request->requestor_id : Auth::id(),
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
            // Log error or ignore if mail fails
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load(['requestor', 'platform', 'host', 'slots']);
        return view('meetings.show', compact('meeting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $meeting->load(['requestor', 'slots']);
        $platforms = Platform::orderBy('name')->get();
        $hosts = Host::orderBy('name')->get();
        $statuses = Meeting::getStatuses();

        return view('meetings.edit', compact('meeting', 'platforms', 'hosts', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $request->validate([
            'status' => 'required|in:pending,scheduled,conflict,cancelled',
            'platform_id' => 'nullable|exists:platforms,id',
            'host_id' => 'nullable|exists:hosts,id',
            'meeting_details' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $meeting->update($request->only(['status', 'platform_id', 'host_id', 'meeting_details', 'description']));

        // If scheduled, all slots are approved; otherwise, none are.
        $isApproved = ($request->status === Meeting::STATUS_SCHEDULED);
        $meeting->slots()->update(['is_approved' => $isApproved]);

        // Send email update if checkbox is checked
        if ($request->has('send_email')) {
            try {
                Mail::to($meeting->requestor->email)->send(new MeetingUpdated($meeting));
            } catch (\Exception $e) {
                // Log error or ignore
            }
        }

        return redirect()->route('meetings.show', $meeting)
            ->with('success', 'Meeting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        $this->authorize('delete', $meeting);

        $meeting->delete();
        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Check for conflicts via AJAX
     */
    public function checkConflict(Request $request)
    {
        $hostId = $request->host_id;
        $excludeMeetingId = $request->meeting_id;

        // Handle UUID if passed for exclusion
        if ($excludeMeetingId && !is_numeric($excludeMeetingId)) {
            $excludeMeetingId = Meeting::where('uuid', $excludeMeetingId)->value('id');
        }

        if ($request->slot_id) {
            $slot = MeetingSlot::find($request->slot_id);
            if (!$slot)
                return response()->json(['conflict' => false]);

            $hasConflict = MeetingSlot::hasConflict($hostId, $slot->meeting_date, $slot->start_time, $slot->end_time, $excludeMeetingId);
        } else {
            $hasConflict = MeetingSlot::hasConflict($hostId, $request->date, $request->start, $request->end, $excludeMeetingId);
        }

        return response()->json(['conflict' => $hasConflict]);
    }

    /**
     * Fetch events for FullCalendar via AJAX
     */
    public function calendarEvents(Request $request)
    {
        $user = Auth::user();
        $query = MeetingSlot::whereHas('meeting', function ($q) use ($user) {
            $q->where('status', Meeting::STATUS_SCHEDULED);
            if (!$user->hasRole('Admin')) {
                $q->where('requestor_id', $user->id);
            }
        })->with(['meeting.requestor', 'meeting.host', 'meeting.platform']);

        $slots = $query->get();
        $events = [];

        foreach ($slots as $slot) {
            $events[] = [
                'id' => $slot->meeting_id,
                'title' => $slot->meeting->topic . ($slot->meeting->host ? ' (' . $slot->meeting->host->name . ')' : ''),
                'start' => $slot->meeting_date->format('Y-m-d') . 'T' . $slot->start_time,
                'end' => $slot->meeting_date->format('Y-m-d') . 'T' . $slot->end_time,
                'url' => route('meetings.show', $slot->meeting->uuid),
                'backgroundColor' => '#2962ff',
                'borderColor' => '#2962ff',
                'textColor' => '#fff',
            ];
        }

        return response()->json($events);
    }
}
