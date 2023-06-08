<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Room;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JsonController;

class TopController extends Controller
{
    //ルーム作成者
    const ROOM_MAKER = 1;
    //非ルーム作成者
    const NOT_ROOM_MAKER = 0;

    /**
     * トップ画面に遷移する
     *
     * @return void
     */
    public function top(Request $request){
        $room_maker_flg = $request['room_maker_flg'];
        $room_no = $request['room_no'];

        if(isset($room_no)){
            $jsonController = new JsonController;
            $json_data =$jsonController->get_file($room_no)[0];
            if($json_data['delete_flg'] == 0){
                //ルーム作成者の場合
                if(isset($room_maker_flg) && $room_maker_flg == 0){
                    $this->out_player_info($room_no);
                //ルーム参加者の場合
                }else if(isset($room_maker_flg) && $room_maker_flg == 1){
                    $this->delete_room_data($room_no);
                }
            }
        }

        $rooms = Room::where('start_flg',0)->where('delete_flg',0)->get();

        return view('top')
        ->with('rooms',$rooms)
        ->with('user',Auth::user());
    }

    /**
     * ルームから自分の情報を抜く
     *
     * @param string $room_no
     * @return void
     */
    public function out_player_info($room_no){
        //dbを更新
        $room = Room::where('room_no',$room_no)->where('delete_flg',0)->first();
        $room->update([
            'player2_id'=>null,
        ]);

        //jsonファイルを更新
       $data = array(
        'player2_id' => null,
        'player2_name' => null,
        'start_flg' => 0,
        'opponent_flg' =>0,
       );
        $jsonController = new JsonController;
        $jsonController->update_file($room_no,$data);

        return redirect()->route('top');
        }

    /**
     * ルームを削除する
     *
     * @param string $room_no
     * @return void
     */
    public function delete_room_data($room_no){
        $room = Room::where('room_no',$room_no)->where('delete_flg',0)->first();
        $room->update([
            'delete_flg' => 1
        ]);
    }

    /**
     * ルームを作成する
     *
     * @return void
     */
    function make_room(Request $request){
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
           'opponent_flg' =>0,
       ]);

       //jsonをファイルを新規作成
       $data = [
           'room_no' => $room_no,
           'user_id' => Auth::id(),
           'player1_name' =>Auth::user()->name,
           'comment' => $request['comment'],
           'delete_flg' => 0,
           'exciting_flg' => $request['exciting_flg'],
           'start_flg' => 0,
           'win_player' => 0,
           'turn_player' => 1,
       ];

       $jsonController = new JsonController;
       $jsonController->make_file($room_no,$data);
    //後でURLじゃなくてこっちをwithで入れて画面遷移するかもしれないので残しておく
    //    $json_data = $jsonController->get_file($room_no)[0];

    return redirect()->route('room', ['room_no'=>$room_no,'room_maker_flg'=>self::ROOM_MAKER]);
   }

   /**
    * 作成済みのルームに入る
    *
    * @return void
    */
   function in_room(Request $request){
       $room_no = $request['room_no'];

       //dbを更新
       $room = Room::where('room_no',$room_no)->where('delete_flg',0)->first();
       $room->update([
           'player2_id'=>Auth::id(),
       ]);
       
       //jsonを更新
       $data = array(
           'player2_id' => Auth::id(),
           'player2_name' => Auth::user()->name,
           'opponent_flg' => 1,
       );
       
       //jsonファイルを更新
       $jsonController = new JsonController;
       $jsonController->update_file($room_no,$data);

       return redirect()->route('room', ['room_no'=>$room_no,'room_maker_flg'=>self::NOT_ROOM_MAKER]);
       
   }

}
