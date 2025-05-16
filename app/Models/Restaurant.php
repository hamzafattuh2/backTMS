<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'location', 'description', 'cuisine', 'price_range', 'contact_email', 'contact_phone', 'is_active'
    ];
} 