<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id', 'user_id', 'status', 'booking_date', 'special_requests', 'payment_status'
    ];
    public function hotel() { return $this->belongsTo(Hotel::class); }
    public function user() { return $this->belongsTo(User::class); }
} 