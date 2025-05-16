<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingChairTrip extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_id', 'user_id', 'number_of_chairs', 'status', 'booking_date', 'special_requests', 'payment_status'
    ];
    public function trip() { return $this->belongsTo(Trip::class); }
    public function user() { return $this->belongsTo(User::class); }
} 