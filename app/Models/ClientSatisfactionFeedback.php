<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSatisfactionFeedback extends Model
{
    use HasFactory;

    protected $table = 'client_satisfaction_feedbacks';

    protected $fillable = [
        'ticket_id',
        'ticket_id',
        'cc1_awareness',
        'cc2_visibility',
        'cc3_helpfulness',
        'rating_overall',
        'rating_responsiveness',
        'rating_reliability',
        'rating_access_facilities',
        'rating_communication',
        'rating_costs',
        'rating_integrity',
        'rating_assurance',
        'rating_outcome',
        'rating_resource_speaker',
        'rating_remarks',
        'comments',
        'signature',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
