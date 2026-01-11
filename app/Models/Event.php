<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_type',
        'classification',
        'venue_type',
        'venue_platform',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'status',
        'expected_participants',
        'organizer_id',
        'registration_fields',
        'enable_qr',
        'disable_registration',
        'banner_image',
        'uuid',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_fields' => 'array',
        'enable_qr' => 'boolean',
        'disable_registration' => 'boolean',
    ];

    public function formFields()
    {
        return $this->hasMany(EventFormField::class)->orderBy('order');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function dates()
    {
        return $this->hasMany(EventDate::class)->orderBy('date')->orderBy('start_time');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Get the computed status based on event dates.
     * Returns: upcoming, ongoing, or completed
     */
    public function getComputedStatusAttribute()
    {
        if ($this->status === 'cancelled') {
            return 'cancelled';
        }

        $now = now();
        $dates = $this->getRelationValue('dates');

        if ($dates->isEmpty()) {
            return $this->status ?? 'upcoming';
        }

        // Get the first and last event dates with times
        $firstDate = $dates->first();
        $lastDate = $dates->last();

        $eventStart = \Carbon\Carbon::parse($firstDate->date->format('Y-m-d') . ' ' . $firstDate->start_time);
        $eventEnd = \Carbon\Carbon::parse($lastDate->date->format('Y-m-d') . ' ' . $lastDate->end_time);

        if ($now->lt($eventStart)) {
            return 'upcoming';
        } elseif ($now->gt($eventEnd)) {
            return 'completed';
        } else {
            return 'ongoing';
        }
    }

    /**
     * Update the status in the database based on computed status.
     */
    public function updateStatusFromDates()
    {
        $computed = $this->computed_status;
        if ($this->status !== 'cancelled' && $this->status !== $computed) {
            $this->update(['status' => $computed]);
        }
    }
}
