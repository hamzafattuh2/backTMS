<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'address',
        'rating',
        'number_of_reviews',
        'price_per_night',
        'images',
        'description',
        'stars',
        'amenities',
        'contact_email',
        'contact_phone',
        'is_active',
        'guide_name',
        'available_seats'
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'rating' => 'decimal:1',
        'price_per_night' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Accessor for reviews
    public function getReviewsAttribute()
    {
        return [
            'rating' => $this->rating,
            'number_of_reviews' => $this->number_of_reviews
        ];
    }

    public function bookingHotels() { return $this->hasMany(BookingHotel::class); }
}
