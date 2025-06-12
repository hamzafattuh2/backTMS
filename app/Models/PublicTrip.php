<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTrip extends Model
{
    use HasFactory;

    protected $table = 'public_trips';

    protected $fillable = [
        'user_id',
        'guide_id',
        'name',
        'city',
        'overview',
        'short_overview',
        'images',
        'date_of_tour',
        'meeting_point',
        'language',
        'price_per_person',
        'available_seats',
        'status',
        'visibility',
        'is_removable',
        'is_guide_confirmed'
    ];

    protected $casts = [
        'images' => 'array',
        'date_of_tour' => 'datetime'
    ];
}
