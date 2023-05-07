<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use App\models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class TopController extends Controller
{
    //トップ画面に遷移する
    public function top(){

        $rooms = Room::all();
        // dd($rooms);
        return view('top')
        ->with('rooms',$rooms)
        ->with('user',Auth::user());
    }

    //待機ルームに入る
    public function wait(Request $request){

        //ルームを作成する
        if(isset($request['make_room'])){

            //ランダムなroom_idを作成
            do {
                $room_no = str_pad(rand(0,99999),5,0, STR_PAD_LEFT);
            } while (ROOM::where('room_no', $room_no)->where('delete_flg',0)->exists());

            Room::create([
                'room_no' => $room_no,
                'user_id' => Auth::id(),
                'comment' => $request['comment'],
                'delete_flg' => '0',
                'exciting_flg' => $request['exciting_flg'],
                'start_flg' => '0'
            ]);

            $room = Room::where('room_no',$room_no)->where('delete_flg',0)->first();
            $room_maker = true;
            // return view('wait_room')
            // ->with('room',$room_no)
            // ->with('room_maker',true);

        //他の人の部屋に入室
        }else{
                $room = Room::where('id',$request['room_id'])->where('delete_flg',0)->first();
                $room_maker = false;
                // dd($room);

                $room->update([
                    'opponent_user_id'=>Auth::id(),
                ]);
            
        }

        // dd($room);
        //ルームに入る
        // dd($room->room_no);
        return view('wait_room')
        // ->with('room',$room[0])
        ->with('room',$room)
        ->with('room_maker',$room_maker);
    }
}
