<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HotelsTableSeeder extends Seeder
{
    public function run()
    {
        // حذف جميع البيانات القديمة
        DB::table('hotels')->delete();
        // $additionalHotels = [

        // ];


        $hotels = [
            //1
            [
                'name' => 'Dama_Rose_damascus',
                'city' => 'Damascus',
                'address' => 'Choukry Kouatly Road Damascus',
                'rating' => 5.0,
                'number_of_reviews' => 300,
                'price_per_night' => 250.00,
                'images' => json_encode([
                    'main_image' => 'images/damasrose1.png',
                    'sub_image1' => 'images/damasrose2.png',
                    'sub_image2' => 'images/damasrose3.png',
                    'sub_image3' => 'images/damasrose4.png',
                ]),
                'description' => "The Dama Rose Hotel is a luxurious five-star accommodation in Damascus, known for its elegant design and exceptional hospitality. Guests can enjoy modern amenities, fine dining, and stunning views of the historic city, making it a perfect retreat for travelers exploring Syria's rich cultural heritage.",
                'stars' => 5,
                'amenities' => json_encode(['Free Wi-Fi', 'Spa', 'Fine Dining', 'Parking']),
                'contact_email' => 'contact@damarose.com',
                'contact_phone' => '011 222 9200',
                'is_active' => true,
                'available_rooms' => 25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            //2

            [
                'name' => 'Golden_Mazzeh_damascus',
                'city' => 'Damascus',
                'address' => 'sahet arnos Damascus, Syria',
                'rating' => 4.0,
                'number_of_reviews' => 210,
                'price_per_night' => 150.00,
                'images' => json_encode([
                    'main_image' => 'images/Armitage1.png',
                    'sub_image1' => 'images/Armitage2.png',
                    'sub_image2' => 'images/Armitage3.png',
                    'sub_image3' => 'images/Armitage4.png',
                ]),
                'description' => "The Armitage Hotel in Damascus offers a blend of luxurious accommodations and modern amenities, set against the backdrop of the city’s rich history. With elegant design and warm hospitality, it serves as a perfect retreat for both business and leisure travelers.",

                'amenities' => json_encode(['Free Wi-Fi', 'Gym', 'Parking']),
                'contact_email' => 'contact@goldenmazzeh.com',
                'contact_phone' => '0946 900 059',
                'is_active' => true,
                'stars' => 4,
                'available_rooms' => 40,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],//3
            [
                'name' => 'Armitage hotel',
                'city' => 'Damascus',
                'address' => 'Eastern Mazzeh Roundabout Damascus, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 260,
                'price_per_night' => 200.00,
                'images' => json_encode([
                    'main_image' => 'images/golen1.png',
                    'sub_image1' => 'images/golden2.png',
                    'sub_image2' => 'images/golden3.png',
                    'sub_image3' => 'images/golden4.png',
                ]),
                'description' => "The Golden Mazzeh Hotel in Damascus is a contemporary haven that combines modern amenities with traditional Syrian hospitality. Located in the vibrant Mazzeh district, it offers comfortable accommodations and easy access to the city’s cultural attractions.",
                'stars' => 5,
                'amenities' => json_encode(['Pool', 'Free Wi-Fi', 'Gym']),
                'contact_email' => 'contact@armitage.com',
                'contact_phone' => '011 443 5344',
                'is_active' => true,
                'available_rooms' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                //4 Four_season_damascus
                'name' => 'Four_season_damascus',
                'city' => 'Damascus',
                'address' => 'Shukri Al Qwatli Avenue Damascus, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 500,
                'price_per_night' => 300.00,
                'images' => json_encode([
                    'main_image' => 'images/four1.png',
                    'sub_image1' => 'images/four2.png',
                    'sub_image2' => 'images/four3.png',
                    'sub_image3' => 'images/four4.png',
                ]),
                'description' => "The Four Seasons Hotel in Damascus offers a luxurious sanctuary with stunning views of the historic Old City, blending modern elegance with rich cultural heritage. Guests can enjoy upscale amenities, including fine dining and a tranquil spa, in an atmosphere of refined sophistication.",
                'stars' => 5,
                'amenities' => json_encode(['Spa', 'Fine Dining', 'Free Wi-Fi', 'Pool']),
                'contact_email' => 'contact@fourseasonsdamascus.com',
                'contact_phone' => '0922282628',
                'is_active' => true,
                'available_rooms' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // --- فندق Sheraton Aleppo ---
            //5
            [
                'name' => 'Sheraton_Aleppo_Hotel',
                'city' => 'Aleppo',
                'address' => 'bab alfarag Aleppo, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 0,
                'price_per_night' => 300.00,
                'images' => json_encode([
                    'main_image' => 'images/sheratonalepo1.png',
                    'sub_image1' => 'images/sheratonalepo2.png',
                    'sub_image2' => 'images/sheratonalepo3.png',
                    'sub_image3' => 'images/sheratonalepo4.png',
                ]),
                //6
                'description' => "The Sheraton Hotel in Aleppo, a luxurious oasis amid the city's rich history...",

                'amenities' => json_encode(['Free Wi-Fi', 'Fine Dining', 'Pool', 'Spa']),
                'contact_email' => null,
                'contact_phone' => '021 212 1111',
                'is_active' => true,
                'stars' => 5,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            //5
            // --- فندق Arman Hotel ---
            [
                'name' => 'Arman_Hotel',
                'city' => 'Aleppo',
                'address' => 'bab alfarag Aleppo, Syria',
                'rating' => 4.0,
                'number_of_reviews' => 0,
                'price_per_night' => 200.00,
                'images' => json_encode([
                    'main_image' => 'images/Arman1.png',
                    'sub_image1' => 'images/Arman2.png',
                    'sub_image2' => 'images/Arman3.png',
                    'sub_image3' => 'images/Arman4.png',
                ]),

                'description' => "The Arman Hotel in Aleppo is a well-regarded establishment...",

                'amenities' => json_encode(['Free Wi-Fi', 'Parking']),
                'contact_email' => null,
                'contact_phone' => '0219778',
                'is_active' => true,
                'stars' => 4,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // --- فندق Laurus Hotel ---
            [
                'name' => 'Laurus_Hotel_Aleppo',
                'city' => 'Aleppo',
                'address' => 'Al Muhafaza - opposite to Souk AL Entaj Aleppo, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 0,
                'price_per_night' => 300.00,
                'images' => json_encode([
                    'main_image' => 'images/laurus1.png',
                    'sub_image1' => 'images/laurus2.png',
                    'sub_image2' => 'images/laurus3.png',
                    'sub_image3' => 'images/laurus4.png',
                ]),
                'description' => "The Laurus Hotel in Aleppo is a modern establishment offering comfortable accommodations...",

                'amenities' => json_encode(['Gym', 'Free Wi-Fi']),
                'contact_email' => 'contact@armitage.com',
                'contact_phone' => '+963 992 440 080',
                'is_active' => true,
                'stars' => 5,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            //lohn
            // // --- فندق Shahba Aleppo Hotel ---
            [
                'name' => 'Shahba_Aleppo_Hotel',
                'city' => 'Aleppo',
                'address' => 'Louai Kayali Street Aleppo, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 0,
                'price_per_night' => 350.00,
                'images' => json_encode([
                    'main_image' => 'images/shahba1.png',
                    'sub_image1' => 'images/shahba2.png',
                    'sub_image2' => 'images/shahba3.png',
                    'sub_image3' => 'images/shahba4.png',
                ]),
                'description' => "The Shahba Hotel in Aleppo is a historic establishment known for its iconic architecture...",

                'amenities' => json_encode(['Free Wi-Fi', 'Spa', 'Parking']),
                'contact_email' => null,
                'contact_phone' => 'contact@Shahba_Aleppo_Hotel.com',
                'is_active' => true,
                'stars' => 5,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Homs_grand_hotel',
                'city' => 'Homs',
                'address' => 'Kahlid bn Waled Road, Syria',
                'rating' => 3.5,
                'number_of_reviews' => 0,
                'price_per_night' => 150.00,
                'images' => json_encode([
                    'main_image' => 'images/homsgrandhotel1.png',
                    'sub_image1' => 'images/homsgrandhotel2.png',
                    'sub_image2' => 'images/homsgrandhotel3.png',
                    'sub_image3' => 'images/homsgrandhotel4.png',
                ]),
                'description' => "The Homs Grand Hotel in Homs, Syria, is a prominent establishment offering comfortable accommodations and modern amenities in a historic city.",

                'amenities' => json_encode(['Free Wi-Fi', 'Parking']),
                'contact_email' => null,
                'contact_phone' => '0944666848',

                'is_active' => true,
                'stars' => 3,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'New_basman_Hotel',
                'city' => 'Homs',
                'address' => 'Al midan, Homs, Syria',
                'rating' => 3.5,
                'number_of_reviews' => 0,
                'price_per_night' => 100.00,
                'images' => json_encode([
                    'main_image' => 'images/newbasman1.png',
                    'sub_image1' => 'images/newbasman2.png',
                    'sub_image2' => 'images/newbasman3.png',
                    'sub_image3' => 'images/newbasman4.png',
                ]),
                'description' => "The new Basman Hotel in Homs offers modern accommodations with stylish decor and essential amenities.",

                'amenities' => json_encode(['Free Wi-Fi']),
                'contact_email' => null,
                'contact_phone' => '0925362788',
                'is_active' => true,
                'stars' => 3,
                'available_seats' => null,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Safir_Hotel_Homs',
                'city' => 'Homs',
                'address' => 'Al midan, Homs, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 0,
                'price_per_night' => 300.00,
                'images' => json_encode([
                    'main_image' => 'images/safir1.png',
                    'sub_image1' => 'images/safir2.png',
                    'sub_image2' => 'images/safir3.png',
                    'sub_image3' => 'images/safir14.png',
                ]),
                'description' => "The new Safir Hotel in Homs offers modern luxury with elegantly designed accommodations and premier amenities.",

                'amenities' => json_encode(['Free Wi-Fi', 'Spa', 'Parking']),
                'contact_email' => null,
                'is_active' => true,
                'stars' => 5,
                'available_seats' => null,
                'contact_phone' => '0922288837',

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Alwaleed_Hotel_Homs',
                'city' => 'Homs',
                'address' => 'Al Kharab Road, Homs, Syria',
                'rating' => 3.0,
                'number_of_reviews' => 0,
                'price_per_night' => 70.00,
                'images' => json_encode([
                    'main_image' => 'images/alwaleed1.png',
                    'sub_image1' => 'images/alwaleed2.png',
                    'sub_image2' => 'images/alwaleed3.png',
                    'sub_image3' => 'images/alwaleed4.png',
                ]),
                'description' => "The Alwaleed Hotel in Homs offers comfortable accommodations with modern amenities.",

                'amenities' => json_encode(['Free Wi-Fi']),
                'contact_email' => null,
                'contact_phone' => '0977755475',
                'is_active' => true,

                'stars' => 3,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Afamia_Hotel_Resort',
                'city' => 'Lattakia',
                'address' => 'Al Kharab Latakia Blue Beach Road',
                'rating' => 5.0,
                'number_of_reviews' => 0,
                'price_per_night' => 300.00,
                'images' => json_encode([
                    'main_image' => 'images/afamia1.png',
                    'sub_image1' => 'images/afamia2.png',
                    'sub_image2' => 'images/afamia3.png',
                    'sub_image3' => 'images/afamia4.png',
                ]),
                'description' => "The Afamia Resort Hotel in Lattakia offers a stunning blend of luxury and natural beauty with views of the Mediterranean Sea.",

                'amenities' => json_encode(['Pool', 'Fine Dining', 'Spa']),
                'contact_email' => null,
                'contact_phone' => '0922837222',
                'is_active' => true,
                'stars' => 5,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Golden_Beach_Hotel',
                'city' => 'Lattakia',
                'address' => 'Lattaquie, Al Ladhiqiyah, Syria',
                'rating' => 5.0,
                'number_of_reviews' => 0,
                'price_per_night' => 270.00,
                'images' => json_encode([
                    'main_image' => 'images/goldenbeach1.png',
                    'sub_image1' => 'images/goldenbeach2.png',
                    'sub_image2' => 'images/goldenbeach3.png',
                    'sub_image3' => 'images/goldenbeach4.png',
                ]),
                'description' => "The Golden Beach Hotel Resort in Lattakia offers a stunning beachfront experience with luxurious accommodations.",

                'amenities' => json_encode(['Beach Access', 'Spa', 'Pool']),
                'contact_email' => null,
                'contact_phone' => '0922224564',
                'is_active' => true,
                'stars' => 5,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Blue_Beach_Hotel',
                'city' => 'Lattakia',
                'address' => 'Lattaquie, Al Ladhiqiyah, Syria',
                'rating' => 4.0,
                'number_of_reviews' => 0,
                'price_per_night' => 270.00,
                'images' => json_encode([
                    'main_image' => 'images/bluebeach1.png',
                    'sub_image1' => 'images/bluebeach2.png',
                    'sub_image2' => 'images/bluebeach3.png',
                    'sub_image3' => 'images/bluebeach4.png',
                ]),
                'description' => "The Blue Beach Hotel in Lattakia offers a picturesque seaside escape with direct beach access and comfortable accommodations.",

                'amenities' => json_encode(['Free Wi-Fi', 'Beach Access']),
                'contact_email' => null,

                'contact_phone' => '0955 004 003',

                'is_active' => true,
                'stars' => 4,
                'available_seats' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            //lohn2
            [
                'name' => 'Miramar_Hotel',
                'city' => 'Lattakia',
                'address' => 'Lattaquie, Al Ladhiqiyah, Syria',
                'rating' => 3.5,
                'number_of_reviews' => 0,
                'price_per_night' => 100.00,
                'images' => json_encode([
                    'main_image' => 'images/miramar1.png',
                    'sub_image1' => 'images/miramar2.png',
                    'sub_image2' => 'images/miramar3.png',
                    'sub_image3' => 'images/miramar4.png',
                ]),
                'description' => "The Miramar Hotel in Lattakia boasts stunning views of the Mediterranean Sea and elegant accommodations.",
                'stars' => 3,
                'amenities' => json_encode(['Free Wi-Fi']),
                'contact_email' => null,
                'available_seats' => null,
                'contact_phone' => '041 456 057',
                'is_active' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // $hotels = array_merge($additionalHotels, $hotels);
        DB::table('hotels')->insert($hotels);
    }
}
