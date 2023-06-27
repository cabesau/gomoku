<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Room;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\JsonController;

class RoomController extends Controller
{
    /**
     * 待機室に遷移する
     *
     * @return void
     */
    function room_top(Request $request, $room_no, $room_maker_flg){

        return view('room')
        ->with([
            'room_no'=>$room_no,
            'room_maker_flg'=>$room_maker_flg,
            ]);
    }

    /**
     * 待機室に対戦相手が来たかどうかを判別
     *
     * @param Request $request
     * @return void
     */
    function check_players_in_room(Request $request){
        $room_no = $request['room_no'];
        $data = Room::where('room_no',$room_no)->first();
        
        //対戦相手がいればsuccessを返す
        if ($data['player2_id']) {
            return 'success';
        }
    
        // 新しいデータがない場合は、204 No Contentを返す
        return response()->noContent();
        
    }

    /**
     * ゲームを開始したかどうかチェック
     *
     * @param Request $request
     * @return void
     */
    function check_game_started(Request $request){
        $room_no = $request['room_no'];
        $file_path = storage_path("app/json/{$room_no}.json");

        //ゲームが開始されていればsuccessを返す
        if (File::exists($file_path)) {
            $json_data = File::get($file_path);
            // dd($json_data);
            $data = json_decode($json_data,true);
            if($data[0]['start_flg'] == '1'){
                return 'success';
            }
        }
         // 新しいデータがない場合は、204 No Contentを返す
        return response()->noContent();
    }

    /**
     * ゲーム終了情報を更新する
     *
     * @param Request $request
     * @return void
     */
    function update_finish_info(Request $request){
        $room_no = $request['room_no'];

        //dbを更新
        $room = Room::where('room_no',$room_no)->where('delete_flg',0)->first();
        $room->update([
        'delete_flg' => 1
        ]);
        
        //jsonファイルを更新
        $jsonController = new JsonController;
        $json_data = $jsonController->get_file($room_no)[0];
        $json_data['delete_flg'] = 1;
        $jsonController->update_file($room_no,$json_data);

        return 'success';
    }
}
