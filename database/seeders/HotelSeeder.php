


<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;

class HotelSeeder extends Seeder
{
 public function run()
    {
        $hotel = [
            'name' => 'Sunrise Resort',
            'location' => 'Dead Sea, Jordan',
            'description' => 'A luxury resort with a sea view.',
            'price_per_night' => 120.00,
            'stars' => 5,
            'amenities' => json_encode([
                'wifi' => true,
                'ac' => true
            ]),
            'contact_email' => 'info@sunriseresort.com',
            'contact_phone' => '+962799999999',
            'is_active' => true
        ];  [
            'name' => 'Damascus Palace Hotel',
            'location' => 'Damascus, Syria',
            'description' => 'Elegant hotel in the heart of Damascus with traditional Syrian hospitality.',
            'price_per_night' => 85.50,
            'stars' => 4,
            'amenities' => json_encode([
                'wifi' => true,
                'ac' => true
            ]),
            'contact_email' => 'contact@damascuspalace.sy',
            'contact_phone' => '+963112345678',
            'is_active' => true
        ]; [
            'name' => 'Damascus Palace Hotel',
            'location' => 'Damascus, Syria',
            'description' => 'Elegant hotel in the heart of Damascus with traditional Syrian hospitality.',
            'price_per_night' => 85.50,
            'stars' => 4,
            'amenities' => json_encode([
                'wifi' => true,
                'ac' => true
            ]),
            'contact_email' => 'contact@damascuspalace.sy',
            'contact_phone' => '+963112345678',
            'is_active' => true
        ];
          [
            'name' => 'Damascus Palace Hotel',
            'location' => 'Damascus, Syria',
            'description' => 'Elegant hotel in the heart of Damascus with traditional Syrian hospitality.',
            'price_per_night' => 85.50,
            'stars' => 4,
            'amenities' => json_encode([
                'wifi' => true,
                'ac' => true
            ]),
            'contact_email' => 'contact@damascuspalace.sy',
            'contact_phone' => '+963112345678',
            'is_active' => true
        ];

        DB::table('hotels')->insert($hotel);
    }
}





// namespace Database\Seeders;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use App\Models\Hotel;

// class HotelSeeder extends Seeder
// {
//  public function run()
//     {
//         $hotel = [
//             'name' => 'Sunrise Resort',
//             'location' => 'Dead Sea, Jordan',
//             'description' => 'A luxury resort with a sea view.',
//             'price_per_night' => 120.00,
//             'stars' => 5,
//             'amenities' => json_encode([
//                 'wifi' => true,
//                 'ac' => true
//             ]),
//             'contact_email' => 'info@sunriseresort.com',
//             'contact_phone' => '+962799999999',
//             'is_active' => true
//         ],  [
//             'name' => 'Damascus Palace Hotel',
//             'location' => 'Damascus, Syria',
//             'description' => 'Elegant hotel in the heart of Damascus with traditional Syrian hospitality.',
//             'price_per_night' => 85.50,
//             'stars' => 4,
//             'amenities' => json_encode([
//                 'wifi' => true,
//                 'ac' => true
//             ]),
//             'contact_email' => 'contact@damascuspalace.sy',
//             'contact_phone' => '+963112345678',
//             'is_active' => true
//         ],  [
//             'name' => 'Damascus Palace Hotel',
//             'location' => 'Damascus, Syria',
//             'description' => 'Elegant hotel in the heart of Damascus with traditional Syrian hospitality.',
//             'price_per_night' => 85.50,
//             'stars' => 4,
//             'amenities' => json_encode([
//                 'wifi' => true,
//                 'ac' => true
//             ]),
//             'contact_email' => 'contact@damascuspalace.sy',
//             'contact_phone' => '+963112345678',
//             'is_active' => true
//         ],
//           [
//             'name' => 'Damascus Palace Hotel',
//             'location' => 'Damascus, Syria',
//             'description' => 'Elegant hotel in the heart of Damascus with traditional Syrian hospitality.',
//             'price_per_night' => 85.50,
//             'stars' => 4,
//             'amenities' => json_encode([
//                 'wifi' => true,
//                 'ac' => true
//             ]),
//             'contact_email' => 'contact@damascuspalace.sy',
//             'contact_phone' => '+963112345678',
//             'is_active' => true
//         ];

//         DB::table('hotels')->insert($hotel);
//     }
// }










//     public function run()
//     {
//         Hotel::create([
//             'name' => 'Sunset Paradise Hotel',
//             'location' => 'Cairo, Egypt',
//             'description' => 'A luxurious hotel with stunning views of the Nile.',
//             'price_per_night' => 150.00,
//             'stars' => 4,
//             'amenities' => 'Wi-Fi,Pool,Gym,Spa',
//             'contact_email' => 'info@sunsetparadise.com',
//             'contact_phone' => '+202123456789',
//             'is_active' => true,
//         ]);

//         Hotel::create([
//             'name' => 'Desert Dream Resort',
//             'location' => 'Luxor, Egypt',
//             'description' => 'A peaceful resort near the ancient temples of Luxor.',
//             'price_per_night' => 100.00,
//             'stars' => 3,
//             'amenities' => 'Wi-Fi,Restaurant,Parking',
//             'contact_email' => 'contact@desertdream.com',
//             'contact_phone' => '+20981234567',
//             'is_active' => true,
//         ]);
//     }
// }
