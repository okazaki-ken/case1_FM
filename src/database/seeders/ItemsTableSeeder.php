<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'name'=>'腕時計',
            'price'=> '15000',
            'category'=>'Rolax',
            'condition'=>'良好',
            'type' => 'メンズ',
            'explanation'=>'スタイリッシュなデザインのメンズ腕時計',
            'item_image' => 'images/items/clock.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 2,
            'name'=>'HDD',
            'price'=> '5000',
            'category'=>'西芝',
            'condition'=>'目立った傷や汚れなし',
            'type' => '家電',
            'explanation'=>'高速で信頼性の高いハードディスク',
            'item_image' => 'images/items/hdd.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 3,
            'name'=>'玉ねぎ3束',
            'price'=> '300',
            'category'=>'なし',
            'condition'=>'やや傷や汚れあり',
            'type' => 'キッチン',
            'explanation'=>'新鮮な玉ねぎ3束のセット',
            'item_image' => 'images/items/onion.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 4,
            'name'=>'革靴',
            'price'=> '4000',
            'category'=>null,
            'condition'=>'状態が悪い',
            'type' => 'メンズ,アクセサリー',
            'explanation'=>'クラシックなデザインの革靴',
            'item_image' => 'images/items/shoes.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 5,
            'name'=>'ノートPC',
            'price'=> '45000',
            'category'=>null,
            'condition'=>'良好',
            'type' => 'インテリア',
            'explanation'=>'高性能なノートパソコン',
            'item_image' => 'images/items/pc.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 6,
            'name'=>'マイク',
            'price'=> '8000',
            'category'=>'なし',
            'condition'=>'目立った傷や汚れなし',
            'type' => 'ファッション',
            'explanation'=>'高音質のレコーディング用マイク',
            'item_image' => 'images/items/mic.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 7,
            'name'=>'ショルダーバッグ',
            'price'=> '3500',
            'category'=>null,
            'condition'=>'やや傷や汚れあり',
            'type' => 'レディース',
            'explanation'=>'おしゃれなショルダーバッグ',
            'item_image' => 'images/items/bag.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 8,
            'name'=>'タンブラー',
            'price'=> '500',
            'category'=>'なし',
            'condition'=>'状態が悪い',
            'type' => '本',
            'explanation'=>'使いやすいタンブラー',
            'item_image' => 'images/items/tumbler.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 9,
            'name'=>'コーヒーミル',
            'price'=> '4000',
            'category'=>'Starbacks',
            'condition'=>'良好',
            'explanation'=>'手動のコーヒーミル',
            'type' => 'スポーツ',
            'item_image' => 'images/items/coffee.jpg',
        ];
        DB::table('items')->insert($param);
        
        $param = [
            'user_id' => 10,
            'name'=>'メイクセット',
            'price'=> '2500',
            'category'=>null,
            'condition'=>'目立った傷や汚れなし',
            'type' => 'おもちゃ',
            'explanation'=>'便利なメイクアップセット',
            'item_image' => 'images/items/makeset.jpg',
        ];
        DB::table('items')->insert($param);
        

    }
}
