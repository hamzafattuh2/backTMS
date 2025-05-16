<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletionReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_id', 'submitted_by', 'report_details', 'photos', 'status'
    ];
    public function trip() { return $this->belongsTo(Trip::class); }
} 