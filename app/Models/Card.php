<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'card_number',
        'expire_time',
        'cvv',
        'card_holder'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}
