<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Room;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JsonController;

class TopController extends Controller
{
    //トップ画面に遷移する
    public function top(){
        $rooms = Room::where('start_flg',0)->where('delete_flg',0)->get();

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
            } while (ROOM::where('room_no', $room_no)->where('start_flg',0)->where('delete_flg',0)->exists());

            //レコードを追加
            Room::create([
                'room_no' => $room_no,
                'user_id' => Auth::id(),
                'comment' => $request['comment'],
                'delete_flg' => 0,
                'exciting_flg' => $request['exciting_flg'],
                'start_flg' => 0,
            ]);

            $room = Room::where('room_no',$room_no)->where('delete_flg',0)->first();
            $room_maker = true;

            //jsonをファイルを新規作成
            $data = [
                'room_no' => $room_no,
                'user_id' => Auth::id(),
                'player1_name' =>Auth::user()->name,
                'comment' => $request['comment'],
                'delete_flg' => 0,
                'exciting_flg' => (integer)$request['exciting_flg'],
                'start_flg' => 0,
            ];

            $jsonController = new JsonController;
            $jsonController->make_file($room_no,$data);

        //他の人の部屋に入室
        }else{
            //dbを更新
            $room = Room::where('id',$request['room_id'])->where('delete_flg',0)->first();
            $room_maker = 0;
            $room->update([
                'player2_id'=>Auth::id(),
            ]);

            //jsonを更新
            $room_no = $room['room_no'];
            $data = array(
                'player2_id' => Auth::id(),
                'player2_name' => Auth::user()->name,
            );
            $jsonController = new JsonController;
            $jsonController->update_file($room_no,$data);

        }

        //ルームに入る
        return view('wait_room')
        ->with('room',$room)
        ->with('room_maker',$room_maker);
    }
}
