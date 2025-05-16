<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'guide_id', 'title', 'description', 'start_date', 'end_date', 'language_guide', 'days_count', 'price', 'status', 'public_or_private', 'delete_able'
    ];
    public function owner() { return $this->belongsTo(User::class, 'user_id'); }
    public function guide() { return $this->belongsTo(User::class, 'guide_id'); }
    public function tripDays() { return $this->hasMany(TripDay::class); }
    public function bookingChairTrips() { return $this->hasMany(BookingChairTrip::class); }
    public function tripPriceSuggestions() { return $this->hasMany(TripPriceSuggestion::class); }
    public function feedbacks() { return $this->hasMany(Feedback::class); }
    public function completionReports() { return $this->hasMany(CompletionReport::class); }
} 