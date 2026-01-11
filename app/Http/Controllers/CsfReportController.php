<?php

namespace App\Http\Controllers;

use App\Models\ClientSatisfactionFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CsfReportController extends Controller
{
    /**
     * Display the CSF Reports (Dashboard + Feedback in tabs).
     */
    public function index(Request $request)
    {
        // Date range filter
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Base query with date filter
        $query = ClientSatisfactionFeedback::query();
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        // Dashboard data
        $totalResponses = (clone $query)->count();
        $averageRating = (clone $query)->avg('rating_overall');

        // Calculate Net Satisfaction (% of ratings 4 or 5)
        $satisfiedCount = (clone $query)->where('rating_overall', '>=', 4)->count();
        $netSatisfaction = $totalResponses > 0 ? round(($satisfiedCount / $totalResponses) * 100) : 0;

        // Calculate average for each criteria (using cloned query)
        $criteriaAverages = [
            'Responsiveness' => (clone $query)->avg('rating_responsiveness'),
            'Reliability' => (clone $query)->avg('rating_reliability'),
            'Access & Facilities' => (clone $query)->avg('rating_access_facilities'),
            'Communication' => (clone $query)->avg('rating_communication'),
            'Cost' => (clone $query)->avg('rating_costs'),
            'Integrity' => (clone $query)->avg('rating_integrity'),
            'Assurance' => (clone $query)->avg('rating_assurance'),
            'Outcome' => (clone $query)->avg('rating_outcome'),
            'Resource Speaker' => (clone $query)->avg('rating_resource_speaker'),
        ];

        // Breakdown by Client Type (with date filter)
        $clientTypesQuery = ClientSatisfactionFeedback::join('tickets', 'client_satisfaction_feedbacks.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.requestor_id', '=', 'users.id');
        if ($fromDate) {
            $clientTypesQuery->whereDate('client_satisfaction_feedbacks.created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $clientTypesQuery->whereDate('client_satisfaction_feedbacks.created_at', '<=', $toDate);
        }
        $clientTypes = $clientTypesQuery->select('users.client_type', DB::raw('count(*) as total'))
            ->groupBy('users.client_type')
            ->pluck('total', 'users.client_type');

        // Feedback list data (with date filter)
        $feedbacks = (clone $query)->with('ticket.requestor')->latest()->get();

        return view('reports.csf.index', compact(
            'totalResponses',
            'averageRating',
            'netSatisfaction',
            'criteriaAverages',
            'clientTypes',
            'feedbacks'
        ));
    }

    /**
     * Update the signature for a feedback entry.
     */
    public function sign(Request $request, $id)
    {
        $request->validate([
            'signature' => 'required|string',
        ]);

        $feedback = ClientSatisfactionFeedback::findOrFail($id);
        $feedback->update([
            'signature' => $request->signature,
        ]);

        return redirect()->route('reports.csf.feedback')
            ->with('success', 'Signature updated successfully for Ticket #' . $feedback->ticket->request_number);
    }
}
