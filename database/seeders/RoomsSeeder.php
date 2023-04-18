<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory;
use Carbon\Carbon;


class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $faker = Factory::create('ja_JP');
        

        for($i = 0; $i <= 10; $i++){
            $userId = $faker->numberBetween($min = 1, $max = 10);
            $randomDate = Carbon::now()->subDays(rand(1, 30));
            $greeting = $faker->randomElement(['おはようございます', 'こんにちは','こんばんは','一緒に対戦しましょう','初心者です']);

            DB::table('rooms')->insert([
                'user_id' => $userId,
                'created_at' =>$randomDate,
                'comment' => $greeting,
            ]);
        }

    }
}
