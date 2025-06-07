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
            'main_image' => 'Tishreen_Park.jpg',
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
            'gallery_images' => ['maaloula1.jpg', 'maaloula2.jpg', 'maaloula3.jpg', 'maaloula4.jpg', 'maaloula5.jpg'],
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

        //قلعة صلاح الدين
        TouristSite::create([
            'name' => 'Saladin Castle',
            'main_image' => 'salah_aldeen_castle.jpg',
            'gallery_images' => ['salah1.jpg', 'salah2.jpg', 'salah3.jpg', 'salah4.jpg', 'salah5.jpg',],
            'address' => 'Latakia ',
            'description' => 'A medieval fortress built by the Crusaders and later captured by Saladin, featuring unique defensive architecture and strategic location',
            'category' => 'historic',
            'average_rating' => 4.7
        ]);

        //اوغاريت
        TouristSite::create([
            'name' => 'Ugarit',
            'main_image' => 'ugarit.jpg',
            'gallery_images' => ['ugarit1.jpg', 'ugarit2.jpg','ugarit3.jpg', 'ugarit4.jpg','ugarit5.jpg'],
            'address' => 'Latakia ',
            'description' => 'Ancient city dating back to the 2nd millennium BC where the first alphabet in history was discovered',
            'category' => 'historic',
            'average_rating' => 4.5
        ]);

        // المتحف الوطني في دمشق
        TouristSite::create([
            'name' => 'National Museum of Damascus',
            'main_image' => 'damascus_museum.jpg',
            'gallery_images' => ['museum1.jpg', 'museum2.jpg', 'museum3.jpg', 'museum4.jpg', 'museum5.jpg', 'museum6.jpg'],
            'address' => 'Damascus',
            'description' => 'Syrias most important museum containing artifacts from various historical periods',
            'category' => 'historic',
            'average_rating' => 4.6
        ]);

        //جبل الشيخ
        TouristSite::create([
            'name' => 'Mount Hermon',
            'main_image' => 'jabal_alcheikh.jpg',
            'gallery_images' => ['jabal1.jpg', 'jabal2.jpg', 'jabal3.jpg', 'jabal4.jpg', 'jabal5.jpg', 'jabal6.jpg', 'jabal7.jpg', 'jabal8.jpg'],
            'address' => 'Rif Dimashq',
            'description' => 'Snow-capped mountain offering panoramic views and winter skiing opportunities',
            'category' => 'nature',
            'average_rating' => 4.4
        ]);

        //نهر بردى
        TouristSite::create([
            'name' => 'Barada River',
            'main_image' => 'barada_river.jpg',
            'gallery_images' => ['barada1.jpg', 'barada2.jpg', 'barada3.jpg', 'barada4.jpg', 'barada5.jpg'],
            'address' => 'Damascus',
            'description' => 'River flowing through Damascus creating green spaces and beautiful natural scenery',
            'category' => 'nature',
            'average_rating' => 4.3
        ]);
        //دير مار موسى
        TouristSite::create([
            'name' => 'Mar Musa Monastery',
            'main_image' => 'mar_musa.jpg',
            'gallery_images' => ['mar1.jpg', 'mar2.jpg', 'mar3.jpg', 'mar4.jpg', 'mar5.jpg'],
            'address' => 'Al-Nabk,syria',
            'description' => 'A 6th-century monastic complex carved into limestone cliffs, known for its vibrant frescoes and interfaith dialogue initiatives. The monastery offers breathtaking desert views and spiritual retreats.',
            'category' => 'historic',
            'average_rating' => 4.7,
            'views_count' => 1250
        ]);



        //المدن المنسية
        TouristSite::create([
            'name' => 'Dead Cities of Syria',
            'main_image' => 'dead_cities.jpg',
            'gallery_images' => ['dead1.jpg', 'dead2.jpg', 'dead3.jpg', 'dead4.jpg', 'dead5.jpg'],
            'address' => 'Idlib,syria',
            'description' => 'A collection of 700 abandoned Byzantine-era settlements featuring well-preserved ruins of churches, villas and bathhouses that showcase early Christian architecture.',
            'category' => 'historic',
            'average_rating' => 4.8,
            'views_count' => 1870
        ]);
        //جزيرة ارواد
        TouristSite::create([
            'name' => 'Arwad Island',
            'main_image' => 'arwad_island.jpg',
            'gallery_images' => ['arwad1.jpg', 'arwad2.jpg', 'arwad3.jpg', 'arwad4.jpg', 'arwad5.jpg'],
            'address' => 'Tartus ,syria',
            'description' => 'Syria\'s only inhabited island featuring a medieval crusader castle, traditional boat-building workshops, and fresh seafood restaurants along its picturesque harbor.',
            'category' => 'historic',
            'average_rating' => 4.5,
            'views_count' => 980
        ]);

        //سوق المدينة
        TouristSite::create([
            'name' => 'Al-Madina Souq',
            'main_image' => 'aleppo_souq.jpg',
            'gallery_images' => ['souq1.jpg', 'souq2.jpg', 'souq3.jpg', 'souq4.jpg', 'souq5.jpg'],
            'address' => 'Old City of Aleppo',
            'description' => 'One of the world\'s largest covered historic markets stretching over 13km, famous for its Ottoman-era architecture, spice stalls, and traditional handicrafts.',
            'category' => 'landmarks',
            'average_rating' => 4.6,
            'views_count' => 2100
        ]);

        //برج صافيتا
        TouristSite::create([
            'name' => 'Safita Tower',
            'main_image' => 'safita_tower.jpg',
            'gallery_images' => ['safita1.jpg', 'safita2.jpg', 'safita3.jpg', 'safita4.jpg', 'safita5.jpg'],
            'address' => 'Safita, Tartus ',
            'description' => 'A 12th-century Crusader watchtower offering 360-degree views of the Syrian coastline and mountains. The tower stands as the best-preserved structure of the Chastel Blanc fortress.',
            'category' => 'historic',
            'average_rating' => 4.4,
            'views_count' => 760
        ]);

    }
}
