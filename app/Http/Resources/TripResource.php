<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'guide_id' => $this->guide_id,
            'title' => $this->title,
            'description' => json_decode($this->description), // تحويل JSON إلى array
            'start_date' => $this->start_date->format('Y-m-d'), // تنسيق التاريخ
            'end_date' => $this->end_date->format('Y-m-d'),
            'language' => $this->languageOfTrip,
            'days_count' => $this->days_count,
            'price' => $this->price,
            'status' => $this->status,
            'type' => $this->public_or_private,
            'is_deletable' => (bool)$this->delete_able,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // العلاقات إذا كانت محملة (Eager Loaded)
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name
                ];
            }),

            'guide' => $this->whenLoaded('guide', function() {
                return $this->guide ? [
                    'id' => $this->guide->id,
                    'name' => $this->guide->name
                ] : null;
            }),

            'private_requests' => $this->whenLoaded('privateRequests', function() {
                return $this->privateRequests->map(function($request) {
                    return [
                        'id' => $request->id,
                        'guide_id' => $request->guide_id,
                        'status' => $request->status
                    ];
                });
            })
        ];
    }
}
