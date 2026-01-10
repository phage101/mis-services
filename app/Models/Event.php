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
        'banner_image',
        'uuid',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_fields' => 'array',
        'enable_qr' => 'boolean',
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
}
