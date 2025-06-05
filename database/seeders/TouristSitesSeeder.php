<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB; // ← تأكد من استيراد DB
use App\Models\TouristSite;
use Illuminate\Database\Seeder;

class TouristSitesSeeder extends Seeder
{
    public function run()
    {
        DB::table('tourist_sites')->truncate();
        TouristSite::create([
            'name' => 'Umayyad Mosque',
            'main_image' => 'umayyad.jpg',
            'gallery_images' => ['umayyad1.jpg', 'umayyad2.jpg', 'umayyad3.jpg', 'umayyad4.jpg'],
            'address' => 'Damascus, Syria',
            'description' => 'TThe Umayyad Mosque in Damascus is one of the oldest and grandest mosques in the world, showcasing stunning Islamic architecture with intricate mosaics and towering minarets, holding religious significance for Muslims, Christians, and Jews alike, and standing as a symbol of Damascus’s spiritual and architectural heritage',
            'category' => 'popular',
            'average_rating' => 4.8
        ]);

        TouristSite::create([
            'name' => 'Bosra Citadel',
            'main_image' => 'Bosra_Citadel.jpeg',
            'gallery_images' => ['Bosra_Citadel1.jpg', 'Bosra_Citadel2.jpg', 'Bosra_Citadel3.jpg', 'Bosra_Citadel4.jpg', 'Bosra_Citadel5.jpg'],
            'address' => 'Daraa, Syria',
            'description' => 'The Umayyad Mosque in Damascus is one of the oldest and grandest mosques in the world.
It features stunning Islamic architecture with intricate mosaics and towering minarets.
The site holds religious significance for Muslims, Christians, and Jews alike.
It stands as a symbol of Damascus’s spiritual and architectural heritage.',
            'category' => 'historic',
            'average_rating' => 4.6
        ]);

        TouristSite::create([
            'name' => 'Tishreen_Park',
            'main_image' => 'tishreen.jpg',
            'gallery_images' => ['tishreen1.jpg', 'tishreen2.jpg', 'tishreen3.jpg', 'tishreen4.jpg', 'tishreen5.jpg'],
            'address' => 'Damascus, Syria',
            'description' => '
Tishreen Park is the largest public park in Damascus, known for its peaceful greenery, where families and friends gather for picnics, walks, and outdoor games, hosting seasonal festivals and cultural events throughout the year, and providing a beloved escape within the city',
            'category' => 'nature',
            'average_rating' => 4.2
        ]);
//قلعة الحصن
        TouristSite::create([
            'name' => 'Krak des Chevaliers',
            'main_image' => 'Krak_des_Chevaliers.jpg',
            'gallery_images' => ['Krak_des_Chevaliers1.jpg', 'Krak_des_Chevaliers2.jpg', 'Krak_des_Chevaliers3.jpg', 'Krak_des_Chevaliers4.jpg'],
            'address' => 'Homs, Syria',
            'description' => '
Krak des Chevaliers is one of the most iconic and well-preserved medieval castles in the world, a key fortress for Crusaders during the 12th and 13th centuries, featuring strong stone walls, towers, and hidden passageways, and overlooking the Homs countryside from its strategic hilltop location',
            'category' => 'popular',
            'average_rating' => 4.9
        ]);
  //تدمر
  TouristSite::create([
    'name' => 'Palmyra',
    'main_image' => 'palmyra.jpg',
    'gallery_images' => ['palmyra1.jpg', 'palmyra2.jpg', 'palmyra3.jpg', 'palmyra4.jpg', 'palmyra5.jpg'],
    'address' => 'Homs Desert, Syria',
    'description' => 'Palmyra was once a thriving ancient city in the Syrian desert known for its grand colonnades, towering temples, and Greco-Roman architecture blended with Eastern influences, it served as a major cultural and trade hub along the Silk Road and remains a powerful symbol of Syria’s historical richness',
    'category' => 'historic',
    'average_rating' => 4.7
]);
//قلعة حلب
TouristSite::create([
    'name' => 'Aleppo Citadel',
    'main_image' => 'aleppo_citadel.jpg',
    'gallery_images' => ['aleppo1.jpg', 'aleppo2.jpg', 'aleppo3.jpg', 'aleppo4.jpg', 'aleppo5.jpg'],
    'address' => 'Aleppo, Syria',
    'description' => 'The Aleppo Citadel is a massive medieval fortress rising above the city skyline with roots dating back to antiquity, it features stone walls, towers, and gates that narrate centuries of history and warfare and remains a prominent landmark representing Aleppo’s resilience and strategic importance',
    'category' => 'popular',
    'average_rating' => 4.6
]);
//افاميا
TouristSite::create([
    'name' => 'Apamea',
    'main_image' => 'apamea.jpg',
    'gallery_images' => ['apamea1.jpg', 'apamea2.jpg', 'apamea3.jpg', 'apamea4.jpg', 'apamea5.jpg'],
    'address' => 'Hama, Syria',
    'description' => 'Apamea is a vast archaeological site known for its mile-long Roman colonnade and ancient ruins spread across rolling hills, it was once a bustling center of philosophy and military power and today it offers a captivating glimpse into the classical world of Syria’s past',
    'category' => 'historic',
    'average_rating' => 4.5
]);
//قصر ابن وردان
TouristSite::create([
    'name' => 'Qasr Ibn Wardan',
    'main_image' => 'qasr_ibn_wardan.jpg',
    'gallery_images' => ['qasr1.jpg', 'qasr2.jpg', 'qasr3.jpg', 'qasr4.jpg', 'qasr5.jpg'],
    'address' => 'Hama Desert, Syria',
    'description' => 'Qasr Ibn Wardan is a unique 6th-century Byzantine palace and church complex in the Syrian desert built with black basalt and yellow bricks, it reflects imperial Roman architectural ambitions and stands as a remote yet remarkable example of Eastern Roman influence in the region',
    'category' => 'historic',
    'average_rating' => 4.4
]);
//دير سيدة صيدنايا
TouristSite::create([
    'name' => 'Our Lady of Saidnaya Monastery',
    'main_image' => 'saidnaya_monastery.jpg',
    'gallery_images' => ['saidnaya1.jpg', 'saidnaya2.jpg', 'saidnaya3.jpg', 'saidnaya4.jpg', 'saidnaya5.jpg'],
    'address' => 'Saidnaya, Syria',
    'description' => 'Our Lady of Saidnaya Monastery is one of the oldest Christian pilgrimage sites in the world perched on a mountain with panoramic views, it houses sacred icons believed to perform miracles and continues to draw pilgrims from across faiths seeking spiritual connection and historical depth',
    'category' => 'landmarks',
    'average_rating' => 4.8
]);

//معلولا
TouristSite::create([
    'name' => 'Maaloula',
    'main_image' => 'maaloula.jpg',
    'gallery_images' => ['maaloula1.jpg', 'maaloula2.jpg', 'maaloula3.jpg', 'maaloula4.jpg',, 'maaloula5.jpg'],
    'address' => 'Rif Dimashq, Syria',
    'description' => 'Maaloula is a scenic mountain village where residents still speak Aramaic the language of Jesus, it is known for its cliffside monasteries carved into rock, narrow ancient pathways, and vibrant religious heritage blending Christian and pre-Christian traditions in harmony',
    'category' => 'historic',
    'average_rating' => 4.7
]);
//نواعير حماة
TouristSite::create([
    'name' => 'Norias of Hama',
    'main_image' => 'norias_hama.jpg',
    'gallery_images' => ['noria1.jpg', 'noria2.jpg', 'noria3.jpg', 'noria4.jpg', 'noria5.jpg'],
    'address' => 'Hama, Syria',
    'description' => 'The Norias of Hama are ancient wooden water wheels that once served as irrigation systems along the Orontes River, admired for their rhythmic turning and architectural charm, they symbolize the ingenuity of medieval hydraulic engineering and remain iconic features of the city’s cultural identity',
    'category' => 'landmarks',
    'average_rating' => 4.6
]);
    }
}
