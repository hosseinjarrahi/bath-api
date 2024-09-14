<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationTariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'باز کردن درب کانتینر (فک پلمپ)',
                'unit' => '1125000',
                'type' => "public",
                'default' => 1,
                'off' => null

            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 20 فوت - تا 3 متر مکعب',
                'unit' => '7786500',
                'type' => "20f",
                'default' => 0,
                'off' => null
            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 20 فوت - تا 10 متر مکعب',
                'unit' => '12199500',
                'type' => "20f",
                'default' => 0,
                'off' => null
            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 20 فوت - بیش از 10 متر مکعب',
                'unit' => '22620000',
                'type' => "20f",
                'default' => 0,
                'off' => null
            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 40 فوت - تا 10 متر مکعب',
                'unit' => '15574500',
                'type' => "40f",
                'default' => 0,
                'off' => null
            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 40 فوت - تا 10 متر مکعب',
                'unit' => '15574500',
                'type' => "40f",
                'default' => 0,
                'off' => '4000'
            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 40 فوت - بیش از  10  تا  20 متر مکعب',
                'unit' => '31102500',
                'type' => "40f",
                'default' => 0,
                'off' => null
            ],
            [
                'name' => 'استریپ و استافینگ کانتینر 40 فوت - بیش از 30 متر مکعب',
                'unit' => '58500000',
                'type' => "40f",
                'default' => 0,
                'off' => null
            ],
            [
                'name' => 'بستن درب کانتینر(حک پلمپ)',
                'unit' => '1125000',
                'type' => "public",
                'default' => 1,
                'off' => null
            ]

        ];
        DB::table('station_tariffs')->insert($data);
    }
}
