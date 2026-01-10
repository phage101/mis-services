<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_participant_id',
        'event_date_id',
        'status',
        'remarks',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'event_participant_id');
    }

    public function date()
    {
        return $this->belongsTo(EventDate::class, 'event_date_id');
    }
}
