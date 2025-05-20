<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TripPriceSuggestion;
use Illuminate\Support\Facades\DB;

class TripPriceSuggestionSeeder extends Seeder
{
    /**
     * شغّل السيدر.
     */
    public function run(): void
    {
        // معرّف الرحلة المطلوب
        $tripId = 11;

        // أمثلة لمرشدين (guide_id) وأسعار مختلفة
        $suggestions = [
            ['guide_id' => 9  ,  'price' => 120.00, 'is_accepted' => false],
            ['guide_id' => 9,  'price' => 115.50, 'is_accepted' => false],
            ['guide_id' => 9,  'price' => 130.00, 'is_accepted' => true],
            ['guide_id' => 9, 'price' => 118.75, 'is_accepted' => false],
        ];

        foreach ($suggestions as $data) {
            TripPriceSuggestion::create([
                'trip_id'     => $tripId,
                'guide_id'    => $data['guide_id'],
                'price'       => $data['price'],
                'is_accepted' => $data['is_accepted'],
            ]);
        }
    }
}
