<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristPlace extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'category_id', 'location', 'contact_phone', 'contact_email', 'website', 'opening_time', 'closing_time', 'features', 'average_rating', 'is_active'
    ];
    public function category() { return $this->belongsTo(Category::class); }
} 