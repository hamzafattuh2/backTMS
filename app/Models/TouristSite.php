<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TouristSite extends Model
{
    /**
     * الحقول التي يمكن تعبئتها جماعياً (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'main_image',
        'gallery_images',
        'address',
        'description',
        'category',
        'views_count',
        'average_rating'
    ];

    /**
     * الحقول التي يجب أن تكون من نوع JSON
     */
    protected $casts = [
        'gallery_images' => 'array'
    ];


    /**
     * علاقة مع التقييمات (إذا كان لديك نموذج Rating)
     */
    // public function ratings()
    // {
    //     return $this->hasMany(Rating::class);
    // }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * تحديث متوسط التقييم
     */
    public function updateAverageRating()
    {
        $this->average_rating = $this->ratings()->avg('rating');
        $this->save();
    }

}
