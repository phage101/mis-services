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
        'uuid',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }

            if (empty($model->request_number)) {
                $now = now();
                $year = $now->year;
                $month = $now->format('m');
                $prefix = sprintf('MTG-%s-%s-', $year, $month);

                // Get the last meeting for this month/year and extract its sequence number
                $lastMeeting = static::where('request_number', 'like', $prefix . '%')
                    ->orderBy('request_number', 'desc')
                    ->first();

                if ($lastMeeting && preg_match('/MTG-\d{4}-\d{2}-(\d+)$/', $lastMeeting->request_number, $matches)) {
                    $nextSequence = (int) $matches[1] + 1;
                } else {
                    $nextSequence = 1;
                }

                $model->request_number = sprintf('MTG-%s-%s-%03d', $year, $month, $nextSequence);
            }
        });
    }

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
