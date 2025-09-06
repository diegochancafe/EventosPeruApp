<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'client_id',
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'event_address',
        'status',
    ];

    // Relationships
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
