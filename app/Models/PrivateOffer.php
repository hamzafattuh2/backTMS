<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateOffer extends Model
{
    protected $table = 'private_offers';

    protected $fillable = [
        'user_id',
        'trip_request_id',
        'price',
    ];

    // Optional: Define relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tripRequest()
    {
        return $this->belongsTo(PrivateTripRequest::class, 'trip_request_id');
    }
}
