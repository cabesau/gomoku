<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory;
use Carbon\Carbon;
use App\Models\Room;


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
            do {
                $room_no = str_pad(rand(0,99999),5,0, STR_PAD_LEFT);
            } while (ROOM::where('room_no', $room_no)->whereNot('delete_flg',1)->exists());

            $randomDate = Carbon::now()->subDays(rand(1, 30));
            $greeting = $faker->randomElement(['おはようございます', 'こんにちは','こんばんは','一緒に対戦しましょう','初心者です']);

            DB::table('rooms')->insert([
                'user_id' => $userId,
                'room_no' => $room_no,
                'created_at' =>$randomDate,
                'comment' => $greeting,
                'delete_flg' => 0,
                'exciting_flg' => 0,
                'start_flg' => 0,
                
            ]);
        }

    }
}
