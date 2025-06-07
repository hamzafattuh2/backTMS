<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tourist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'nationality',
        'passport_number'
    ];

    public function user() { return $this->belongsTo(User::class); }
}
