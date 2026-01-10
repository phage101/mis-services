<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    protected $fillable = [
        'event_id',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class, 'event_date_id');
    }
}
