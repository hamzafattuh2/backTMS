<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'location', 'description', 'price_per_night', 'stars', 'amenities', 'contact_email', 'contact_phone', 'is_active'
    ];
    public function bookingHotels() { return $this->hasMany(BookingHotel::class); }
}
