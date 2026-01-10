<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFLICT = 'conflict';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'date_requested',
        'requestor_id',
        'topic',
        'description',
        'platform_id',
        'host_id',
        'status',
        'meeting_details',
    ];

    protected $casts = [
        'date_requested' => 'date',
    ];

    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function slots()
    {
        return $this->hasMany(MeetingSlot::class);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_CONFLICT => 'Conflict',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
