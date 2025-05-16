<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPriceSuggestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_id', 'guide_id', 'price', 'is_accepted'
    ];
    public function trip() { return $this->belongsTo(Trip::class); }
    public function guide() { return $this->belongsTo(User::class, 'guide_id'); }
} 