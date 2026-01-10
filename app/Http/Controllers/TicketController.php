<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;
use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        $user = Auth::user();

        // Admins see all tickets, users see only their own
        if ($user->hasRole('Admin')) {
            $tickets = Ticket::with('requestor')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $tickets = Ticket::where('requestor_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('tickets.index', compact('tickets'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $urgencies = Ticket::getUrgencies();
        $requestTypes = RequestType::with('categories')->orderBy('name')->get();
        $users = [];
        if (Auth::user()->hasRole('Admin')) {
            $users = User::orderBy('name')->get();
        }
        return view('tickets.create', compact('urgencies', 'requestTypes', 'users'));
    }

    /**
     * Store a newly created ticket.
     */
    public function store(Request $request)
    {
        $rules = [
            'date_requested' => 'required|date',
            'request_type_id' => 'required|exists:request_types,id',
            'category_id' => 'required|exists:categories,id',
            'complaint' => 'required|string',
            'urgency' => 'required|in:low,medium,high,critical',
        ];

        if (Auth::user()->hasRole('Admin')) {
            $rules['requestor_id'] = 'required|exists:users,id';
        }

        $request->validate($rules);

        Ticket::create([
            'date_requested' => $request->date_requested,
            'requestor_id' => Auth::user()->hasRole('Admin') ? $request->requestor_id : Auth::id(),
            'request_type_id' => $request->request_type_id,
            'category_id' => $request->category_id,
            'complaint' => $request->complaint,
            'urgency' => $request->urgency,
            'status' => Ticket::STATUS_PENDING,
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Get categories for a specific request type (for AJAX).
     */
    public function getCategories($requestTypeId)
    {
        $categories = \App\Models\Category::where('request_type_id', $requestTypeId)->orderBy('name')->get();
        return response()->json($categories);
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        // Users can only view their own tickets
        if (!$user->hasRole('Admin') && $ticket->requestor_id !== $user->id) {
            abort(403);
        }

        $ticket->load(['requestor', 'responses.user', 'requestType', 'category']);
        $statuses = Ticket::getStatuses();

        return view('tickets.show', compact('ticket', 'statuses'));
    }

    /**
     * Show the form for editing the specified ticket (admin only).
     */
    public function edit(Ticket $ticket)
    {
        $user = Auth::user();

        if (!$user->hasRole('Admin')) {
            abort(403);
        }

        $statuses = Ticket::getStatuses();
        $urgencies = Ticket::getUrgencies();
        $requestTypes = RequestType::with('categories')->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'statuses', 'urgencies', 'requestTypes', 'users'));
    }

    /**
     * Update the specified ticket (admin only).
     */
    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if (!$user->hasRole('Admin')) {
            abort(403);
        }

        $request->validate([
            'requestor_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,on-going,completed,cancelled',
            'urgency' => 'required|in:low,medium,high,critical',
            'request_type_id' => 'required|exists:request_types,id',
            'category_id' => 'required|exists:categories,id',
            'channel' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'datetime_started' => 'nullable|date',
            'datetime_ended' => 'nullable|date',
        ]);

        $ticket->update($request->only([
            'requestor_id',
            'status',
            'urgency',
            'request_type_id',
            'category_id',
            'channel',
            'remarks',
            'datetime_started',
            'datetime_ended',
        ]));

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket.
     */
    public function destroy(Ticket $ticket)
    {
        $user = Auth::user();

        if (!$user->hasRole('Admin')) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Add a response to a ticket (admin only).
     */
    public function addResponse(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if (!$user->hasRole('Admin')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,on-going,completed,cancelled',
            'action_taken' => 'required|string',
        ]);

        // Create response
        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'status' => $request->status,
            'action_taken' => $request->action_taken,
        ]);

        // Update ticket status
        $ticket->update(['status' => $request->status]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Response added successfully.');
    }
}
