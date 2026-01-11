<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\RequestType;
use App\Models\Category;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated;

class PublicTicketController extends Controller
{
    /**
     * Show the public ticket submission form.
     */
    public function index()
    {
        $urgencies = Ticket::getUrgencies();
        $requestTypes = RequestType::orderBy('name')->get();
        $offices = Office::orderBy('name')->get();

        return view('tickets.public', compact('urgencies', 'requestTypes', 'offices'));
    }

    /**
     * Search users by name for the public form.
     */
    public function searchUser(Request $request)
    {
        $term = $request->get('q');

        // Allow searching by Name OR Email. 
        // We limit to 5 results to allow better matching while preventing mass harvesting.
        $users = User::where('name', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->limit(5)
            ->get(['id', 'name', 'email']);

        $results = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->name . " ({$user->email})"
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Get categories for a specific request type.
     */
    public function getCategories($requestTypeId)
    {
        $categories = Category::where('request_type_id', $requestTypeId)->orderBy('name')->get();
        return response()->json($categories);
    }

    /**
     * Get divisions for a specific office.
     */
    public function getDivisions($officeId)
    {
        $divisions = \App\Models\Division::where('office_id', $officeId)->orderBy('name')->get();
        return response()->json($divisions);
    }

    /**
     * Show/Handle ticket tracking.
     */
    public function track(Request $request)
    {
        $search = $request->get('search');
        $tickets = null;

        if ($search) {
            $query = Ticket::query()->with(['requestor', 'requestType', 'category', 'responses.user']);

            // If it looks like an email, search by email via the requestor
            if (filter_var($search, FILTER_VALIDATE_EMAIL)) {
                $query->whereHas('requestor', function ($q) use ($search) {
                    $q->where('email', $search);
                });
            } else {
                // Otherwise search by exact request number
                $query->where('request_number', $search);
            }

            $tickets = $query->orderBy('created_at', 'desc')->get();
        }

        return view('tickets.track', compact('tickets', 'search'));
    }

    /**
     * Store a newly created ticket from the public form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'requestor_name' => 'required|string|max:255',
            'requestor_id' => 'nullable|exists:users,id',
            'email' => 'required_without:requestor_id|nullable|email|unique:users,email',
            'office_id' => 'required_without:requestor_id|nullable|exists:offices,id',
            'division_id' => 'required_without:requestor_id|nullable|exists:divisions,id',
            'client_type' => 'required_without:requestor_id|nullable|string',
            'age_bracket' => 'required_without:requestor_id|nullable|string',
            'request_type_id' => 'required|exists:request_types,id',
            'category_id' => 'required|exists:categories,id',
            'urgency' => 'required|in:low,medium,high,critical',
            'complaint' => 'required|string',
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
                'client_type' => $request->client_type,
                'age_bracket' => $request->age_bracket,
            ]);

            $user->assignRole('User'); // Case sensitive match with PermissionSeeder
            $requestorId = $user->id;

            // Note: In a real app, we'd email the password to the user here.
        }

        $ticket = Ticket::create([
            'date_requested' => now(),
            'requestor_id' => $requestorId,
            'request_type_id' => $request->request_type_id,
            'category_id' => $request->category_id,
            'complaint' => $request->complaint,
            'urgency' => $request->urgency,
            'channel' => 'Public Web Form',
            'status' => Ticket::STATUS_PENDING,
        ]);

        // Send email to requestor
        try {
            Mail::to($ticket->requestor->email)->send(new TicketCreated($ticket));
        } catch (\Exception $e) {
            // Silence mail errors
        }

        return redirect()->route('public.tickets.track', ['search' => $ticket->request_number])
            ->with('success', 'Your request has been submitted successfully (Ticket #' . $ticket->request_number . '). We will contact you soon.');
    }
}
