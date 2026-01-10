<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Meeting;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            'users' => User::count(),
            'tickets' => Ticket::where('status', 'pending')->count(),
            'meetings' => Meeting::where('status', 'scheduled')->count(),
            'events' => Event::where('status', 'upcoming')->count(),
        ];

        $recentTickets = Ticket::with('requestor')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingMeetings = Meeting::with(['host', 'platform'])
            ->where('status', 'scheduled')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $latestParticipants = EventParticipant::with('event')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('kpis', 'recentTickets', 'upcomingMeetings', 'latestParticipants'));
    }
}