<?php

namespace Modules\Service\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'title' => 'ساعت شروع پذیرش',
                'key' => 'start_accept_time',
                'value' => '10:30',
            ],
            [
                'title' => 'ساعت پایان پذیرش',
                'key' => 'end_accept_time',
                'value' => '18:30',
            ],
            [
                'title' => 'حداکثر سرویس قابل رزرو',
                'key' => 'max_reserve',
                'value' => '1',
            ],
            [
                'title' => 'هزینه سرویس (تومان)',
                'key' => 'service_item_price',
                'value' => '10000',
            ],
            [
                'title' => 'چند روز قبل تر بتونه رزرو کنه',
                'key' => 'max_reserve_before_date',
                'value' => '1',
            ],
            [
                'title' => 'چند روز رو بتونه رزرو کنه',
                'key' => 'max_reserve_day',
                'value' => '1',
            ],
            [
                'title' => 'هزینه تاخیر برحسب ساعت',
                'key' => 'delay_price',
                'value' => '10000',
            ],
        ];

        dump('Seeding: ServiceSettings seeder');
        DB::table('service_settings')->insert($settings);
        dump('Seeded:  ServiceSettings seeder');
    }
}
