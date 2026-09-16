<?php

namespace Database\Seeders;

use App\Models\Zone;
use App\Models\Region;
use App\Models\District;
use Illuminate\Database\Seeder;

class GeographySeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            ['name' => 'Coastal Zone', 'code' => 'CST'],
            ['name' => 'Central Zone', 'code' => 'CNT'],
            ['name' => 'Northern Zone', 'code' => 'NTH'],
            ['name' => 'Lake Zone', 'code' => 'LKE'],
            ['name' => 'Southern Highlands', 'code' => 'SHL'],
            ['name' => 'Southern Zone', 'code' => 'STH'],
            ['name' => 'Western Zone', 'code' => 'WST'],
            ['name' => 'Zanzibar Zone', 'code' => 'ZNZ'],
        ];

        foreach ($zones as $zoneData) {
            $zone = Zone::updateOrCreate(['code' => $zoneData['code']], $zoneData);
        }

        $regions = [
            // Coastal
            ['zone_code' => 'CST', 'name' => 'Dar es Salaam', 'code' => 'DAR', 'districts' => ['Ilala', 'Kinondoni', 'Temeke', 'Ubungo', 'Kigamboni']],
            ['zone_code' => 'CST', 'name' => 'Pwani', 'code' => 'PWN', 'districts' => ['Bagamoyo', 'Kibaha', 'Kisarawe', 'Mkuranga', 'Rufiji']],
            ['zone_code' => 'CST', 'name' => 'Morogoro', 'code' => 'MOR', 'districts' => ['Morogoro Urban', 'Kilosa', 'Kilombero', 'Mvomero']],
            // Central
            ['zone_code' => 'CNT', 'name' => 'Dodoma', 'code' => 'DOM', 'districts' => ['Dodoma City', 'Bahi', 'Chamwino', 'Kondoa']],
            ['zone_code' => 'CNT', 'name' => 'Singida', 'code' => 'SGD', 'districts' => ['Singida Urban', 'Iramba', 'Manyoni']],
            // Northern
            ['zone_code' => 'NTH', 'name' => 'Arusha', 'code' => 'ARU', 'districts' => ['Arusha City', 'Arumeru', 'Karatu', 'Monduli']],
            ['zone_code' => 'NTH', 'name' => 'Kilimanjaro', 'code' => 'KLM', 'districts' => ['Moshi Urban', 'Hai', 'Rombo', 'Same', 'Siha']],
            ['zone_code' => 'NTH', 'name' => 'Tanga', 'code' => 'TNG', 'districts' => ['Tanga City', 'Korogwe', 'Lushoto', 'Muheza', 'Pangani']],
            // Lake
            ['zone_code' => 'LKE', 'name' => 'Mwanza', 'code' => 'MWZ', 'districts' => ['Nyamagana', 'Ilemela', 'Magu', 'Sengerema']],
            ['zone_code' => 'LKE', 'name' => 'Mara', 'code' => 'MAR', 'districts' => ['Musoma Urban', 'Bunda', 'Tarime', 'Rorya']],
            ['zone_code' => 'LKE', 'name' => 'Kagera', 'code' => 'KAG', 'districts' => ['Bukoba Urban', 'Muleba', 'Karagwe']],
            // Southern Highlands
            ['zone_code' => 'SHL', 'name' => 'Mbeya', 'code' => 'MBY', 'districts' => ['Mbeya City', 'Chunya', 'Kyela', 'Rungwe']],
            ['zone_code' => 'SHL', 'name' => 'Iringa', 'code' => 'IRG', 'districts' => ['Iringa Municipal', 'Kilolo', 'Mufindi']],
        ];

        foreach ($regions as $r) {
            $zone = Zone::where('code', $r['zone_code'])->first();
            $region = Region::updateOrCreate(
                ['code' => $r['code']],
                [
                    'zone_id' => $zone?->id,
                    'name' => $r['name'],
                    'is_active' => true,
                ]
            );

            foreach ($r['districts'] as $distName) {
                District::updateOrCreate(
                    ['region_id' => $region->id, 'name' => $distName],
                    ['is_active' => true]
                );
            }
        }
    }
}
