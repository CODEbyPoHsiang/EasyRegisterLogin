<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; //要引用寫入的Model
use DB; //使用DB套件

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->delete();//先清空假資料資料表
        //DB::table('notes')->truncate();

        User::factory()->count(12)->create();//假資料要建立的筆數
    }
}
