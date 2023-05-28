<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Room;
use Illuminate\Support\Facades\File;

class RoomController extends Controller
{
    //待機室に対戦相手が来たかどうかを判別
    function check_players_in_room(Request $request){
        $room_no = $request['room_no'];
        $data = Room::where('room_no',$room_no)->first();
        
        //対戦相手がいればsuccessを返す
        if ($data['player2_id']) {
            return 'success';
        }
    
        // // 新しいデータがない場合は、204 No Contentを返す
        return response()->noContent();
        
    }

    //ゲームを開始したかどうかチェック
    function check_game_started(Request $request){
        $room_no = $request['room_no'];
        $file_path = storage_path("app/json/{$room_no}.json");
        

        // dd($file_path);

        //ゲームが開始されていればsuccessを返す
        if (File::exists($file_path)) {
            $json_data = File::get($file_path);
            // dd($json_data);
            $data = json_decode($json_data,true);

            // dd($data);
            
            // if($data['start_flg'] == '1'){
            if($data[0]['start_flg'] == '1'){
                return 'success';
            }
        }

        // // 新しいデータがない場合は、204 No Contentを返す
        return response()->noContent();
    }


    
}
