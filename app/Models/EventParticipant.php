<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'event_id',
        'name',
        'first_name',
        'last_name',
        'organization',
        'designation',
        'age_bracket',
        'sex',
        'province',
        'contact_no',
        'email',
        'type',
        'organization_msme',
        'attendance_status',
        'additional_data',
        'client_type',
    ];

    protected $casts = [
        'additional_data' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class, 'event_participant_id');
    }

    public function getLastNameAttribute($value)
    {
        if (!empty($value))
            return $value;

        // Fallback: try to extract from full name
        $parts = explode(' ', $this->name ?? '');
        return count($parts) > 1 ? array_pop($parts) : ($this->name ?? ''); // Determine if single name is last or first? Usually last name is at end.
    }

    public function getFirstNameAttribute($value)
    {
        if (!empty($value))
            return $value;

        // Fallback
        $parts = explode(' ', $this->name ?? '');
        if (count($parts) > 1) {
            array_pop($parts);
            return implode(' ', $parts);
        }
        return ''; // If only one name, treat as Last Name? Or First? Let's assume Last Name takes precedence for sorting?
        // Actually, if name is "John", last_name="John", first_name="" effectively?
        // Or if name="John", last_name="", first_name="John"?
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
}
