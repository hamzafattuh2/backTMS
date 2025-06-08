<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

class TourGuide extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            //   'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function tripRequests()
{
    return $this->hasMany(PrivateTripRequest::class);
}
    public function guidedTrips() { return $this->hasMany(Trip::class, 'guide_id'); }
}
