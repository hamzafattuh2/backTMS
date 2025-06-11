<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateTripRequest extends Model
{
    protected $table = 'private_trip_requests';

    protected $fillable = [
        'user_id',
        'trip_id',
        'tour_id',
        'start_date',
        'end_date',
        'lang',
        'days',
        'count_days',
        'status',
    ];

    // If you want to use relationships (optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function tourGuide()
    {
        return $this->belongsTo(TourGuide::class, 'tour_id');
    }
}
