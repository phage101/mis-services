<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'meeting_date',
        'start_time',
        'end_time',
        'is_approved',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Check if this slot conflicts with another approved meeting for a specific host.
     */
    public static function hasConflict($host_id, $date, $start, $end, $excludeMeetingId = null)
    {
        return self::where('meeting_date', $date)
            ->where('is_approved', true)
            ->whereHas('meeting', function ($query) use ($host_id, $excludeMeetingId) {
                $query->where('host_id', $host_id)
                    ->where('status', Meeting::STATUS_SCHEDULED);
                if ($excludeMeetingId) {
                    $query->where('id', '!=', $excludeMeetingId);
                }
            })
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            })
            ->exists();
    }
}
