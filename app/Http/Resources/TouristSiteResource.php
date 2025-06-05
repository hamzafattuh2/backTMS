<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TouristSiteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'main_image' => $this->getImageUrl($this->main_image),
            'gallery_images' => $this->getGalleryImagesUrls(),
            'address' => $this->address,
            'description' => $this->description,
            'category' => $this->category,
            'views_count' => $this->views_count,
            'average_rating' => $this->average_rating,
        ];
    }

    protected function getImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        return asset("storage/tourist_sites_pictures/{$imagePath}");
    }

    protected function getGalleryImagesUrls()
    {
        if (empty($this->gallery_images)) {
            return [];
        }

        return collect($this->gallery_images)->map(function ($img) {
            return $this->getImageUrl($img);
        })->toArray();
    }
}
