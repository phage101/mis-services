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
