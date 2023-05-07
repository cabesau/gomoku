<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Room;

class RoomController extends Controller
{
    function check_players_in_room(Request $request){
        $room_no = $request['room_no'];
        $data = Room::where('room_no',$room_no)->first();
        
        //対戦相手がいればsuccessを返す
        if ($data['opponent_user_id']) {
            return 'success';
        }
    
        // // 新しいデータがない場合は、204 No Contentを返す
        return response()->noContent();
        
    }

    function check_game_started(Request $request){
        $room_no = $request['room_no'];
        $data = Room::where('room_no',$room_no)->first();
        
        //対戦相手がいればsuccessを返す
        if ($data['started'] == '1') {
            return 'success';
        }
    
        // // 新しいデータがない場合は、204 No Contentを返す
        return response()->noContent();
    }


    
}
