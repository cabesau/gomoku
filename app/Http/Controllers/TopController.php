<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\Room;
use App\Models\User;

class TopController extends Controller
{
    //トップ画面に遷移する
    public function top(){

        $rooms = Room::all();
        // dd($rooms);
        return view('top')
        ->with('rooms',$rooms);
    }

    //ルームに入る
    public function wait(Request $request){

        if(isset($request['make_room'])){
            

        }

        $room = Room::where('id',$request['room_id'])->get();
        // dd($room);
        return view('wait_room')
        ->with('room',$room[0]);
    }
}
