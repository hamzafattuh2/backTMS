<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'type',
    //     'phone_number',
    //     'gender',
    //     'profile_image'
    // ,'code'
    // ,'expire_at'
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
         //   'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }
    public function bookingHotels() { return $this->hasMany(BookingHotel::class); }
    public function bookingChairTrips() { return $this->hasMany(BookingChairTrip::class); }
    public function trips() { return $this->hasMany(Trip::class, 'user_id'); }
    public function guidedTrips() { return $this->hasMany(Trip::class, 'guide_id'); }
    public function feedbacks() { return $this->hasMany(Feedback::class); }
    public function wallet() { return $this->hasOne(Wallet::class); }
    public function tourist() { return $this->hasOne(Tourist::class); }
    public function tourGuide() { return $this->hasOne(TourGuide::class); }
    public function admin() { return $this->hasOne(Admin::class); }
    public function generateCode()
    {
        $this->timestamps = false;
        $this->code = rand(1000, 9999);
        $this->expire_at = now()->addMinutes(15);
        $this->save();
    }
    public function resetCode()
    {
        $this->timestamps = false;
        $this->code = null;
        $this->expire_at = null;
        $this->save();
    }
}
