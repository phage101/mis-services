<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Meeting;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['office', 'division']);
        $isAdmin = $user->hasRole('Admin');

        $kpis = [
            'users' => $isAdmin ? User::count() : 0,
            'tickets' => $isAdmin ? Ticket::where('status', 'pending')->count() : Ticket::where('status', 'pending')->where('requestor_id', $user->id)->count(),
            'meetings' => $isAdmin ? Meeting::where('status', 'scheduled')->count() : Meeting::where('status', 'scheduled')->where('requestor_id', $user->id)->count(),
            'events' => Event::where('status', 'upcoming')->count(),
        ];

        $recentTicketsQuery = Ticket::with('requestor');
        if (!$isAdmin) {
            $recentTicketsQuery->where('requestor_id', $user->id);
        }
        $recentTickets = $recentTicketsQuery->orderBy('created_at', 'desc')->limit(5)->get();

        $upcomingMeetingsQuery = Meeting::with(['host', 'platform'])->where('status', 'scheduled');
        if (!$isAdmin) {
            $upcomingMeetingsQuery->where('requestor_id', $user->id);
        }
        $upcomingMeetings = $upcomingMeetingsQuery->orderBy('created_at', 'desc')->limit(5)->get();

        $latestParticipants = $isAdmin ? EventParticipant::with('event')->orderBy('created_at', 'desc')->limit(5)->get() : collect();

        return view('dashboard', compact('kpis', 'recentTickets', 'upcomingMeetings', 'latestParticipants', 'user', 'isAdmin'));
    }
}