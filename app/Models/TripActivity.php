<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_day_id', 'title', 'description'
    ];
    public function tripDay() { return $this->belongsTo(TripDay::class); }
} 