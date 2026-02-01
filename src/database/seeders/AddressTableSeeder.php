<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('addresses')->insert([
            [
                'user_id' => 1,
                'post' => '150-0001',
                'address' => '東京都渋谷区神宮前1-1-1',
                'building' => '原宿サンプルビル 101',
                'profile_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'post' => '530-0001',
                'address' => '大阪府大阪市北区梅田1-1-1',
                'building' => '梅田ダミータワー 12F',
                'profile_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'post' => '460-0001',
                'address' => '愛知県名古屋市中区栄1-1-1',
                'building' => 'サカエ仮マンション 202',
                'profile_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
