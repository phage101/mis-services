<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'date_requested',
        'requestor_id',
        'request_type_id',
        'category_id',
        'complaint',
        'urgency',
        'datetime_started',
        'datetime_ended',
        'channel',
        'remarks',
        'status',
        'uuid',
    ];

    protected $casts = [
        'date_requested' => 'date',
        'datetime_started' => 'datetime',
        'datetime_ended' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ONGOING = 'on-going';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const URGENCY_LOW = 'low';
    const URGENCY_MEDIUM = 'medium';
    const URGENCY_HIGH = 'high';
    const URGENCY_CRITICAL = 'critical';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ONGOING => 'On-going',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getUrgencies(): array
    {
        return [
            self::URGENCY_LOW => 'Low',
            self::URGENCY_MEDIUM => 'Medium',
            self::URGENCY_HIGH => 'High',
            self::URGENCY_CRITICAL => 'Critical',
        ];
    }

    public function requestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TicketResponse::class)->orderBy('created_at', 'desc');
    }

    public function feedback(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ClientSatisfactionFeedback::class);
    }

    /**
     * Boot method to handle status changes
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->uuid)) {
                $ticket->uuid = (string) \Illuminate\Support\Str::uuid();
            }

            if (empty($ticket->request_number)) {
                $now = now();
                $year = $now->year;
                $month = $now->format('m');
                $prefix = sprintf('ICT-%s-%s-', $year, $month);

                // Get the last ticket for this month/year and extract its sequence number
                $lastTicket = static::where('request_number', 'like', $prefix . '%')
                    ->orderBy('request_number', 'desc')
                    ->first();

                if ($lastTicket && preg_match('/ICT-\d{4}-\d{2}-(\d+)$/', $lastTicket->request_number, $matches)) {
                    $nextSequence = (int) $matches[1] + 1;
                } else {
                    $nextSequence = 1;
                }

                $ticket->request_number = sprintf('ICT-%s-%s-%03d', $year, $month, $nextSequence);
            }
        });

        static::updating(function ($ticket) {
            $original = $ticket->getOriginal('status');
            $new = $ticket->status;

            // Auto-set datetime_started when changing to on-going
            if ($original !== self::STATUS_ONGOING && $new === self::STATUS_ONGOING && !$ticket->datetime_started) {
                $ticket->datetime_started = now();
            }

            // Auto-set datetime_ended when changing to completed
            if ($original !== self::STATUS_COMPLETED && $new === self::STATUS_COMPLETED && !$ticket->datetime_ended) {
                $ticket->datetime_ended = now();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
