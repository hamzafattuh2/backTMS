<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateTripRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'tourist_id',
        'guide_id',
        'title_request',
        'status'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function tourist()
    {
        return $this->belongsTo(User::class, 'tourist_id');
    }

public function guide()
{
    return $this->belongsTo(TourGuide::class);
}
}
