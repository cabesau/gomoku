<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $faker = Factory::create('ja_JP');
        
        DB::table('users')->insert([
            'name'=> 'りょうた',
            'email' => 'sample@sample',
            'password' =>'1234',
        ]);

        for($i = 0; $i <= 10; $i++){
            $name = $faker->randomElement(['太郎','花子','正志','順子','よういち','さおり','瀬奈','長助','ピー子','チョロ丸','猫丸','しゅんや']);
            DB::table('users')->insert([
                'name'=> $name,
                'email' => $faker->email,
                'password' =>'12345678',
            ]);

        }
    }
}
