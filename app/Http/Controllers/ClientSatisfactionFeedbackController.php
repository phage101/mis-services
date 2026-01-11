<?php

namespace App\Http\Controllers;

use App\Models\ClientSatisfactionFeedback;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ClientSatisfactionFeedbackController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Ticket $ticket)
    {
        // Check if feedback already exists
        if ($ticket->feedback) {
            return view('csf.already-submitted');
        }

        return view('csf.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Ticket $ticket)
    {
        if ($ticket->feedback) {
            return redirect()->back()->with('error', 'Feedback already submitted for this ticket.');
        }

        $validated = $request->validate([
            'cc1_awareness' => 'required|string',
            'cc2_visibility' => 'required|string',
            'cc3_helpfulness' => 'required|string',
            'rating_overall' => 'required|integer|min:1|max:5',
            'rating_responsiveness' => 'required|integer|min:1|max:5',
            'rating_reliability' => 'required|integer|min:1|max:5',
            'rating_access_facilities' => 'required|integer|min:1|max:5',
            'rating_communication' => 'required|integer|min:1|max:5',
            'rating_costs' => 'required|integer|min:1|max:5',
            'rating_integrity' => 'required|integer|min:1|max:5',
            'rating_assurance' => 'required|integer|min:1|max:5',
            'rating_outcome' => 'required|integer|min:1|max:5',
            'rating_resource_speaker' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
            'rating_remarks' => 'nullable|string',
            'signature' => 'nullable|string', // Optional now
            'g-recaptcha-response' => ['required', new \App\Rules\Recaptcha],
        ]);

        // If demographics are passed but not validated, we might want to capture them or ignore them.
        // Since we are not saving them to the feedback table anymore, we can just ignore them here.
        // In Step 473, we removed the block that updates the User model, so we are consistent.


        $feedback = new ClientSatisfactionFeedback($validated);
        $feedback->ticket_id = $ticket->id;
        $feedback->save();

        return view('csf.success');
    }
}
