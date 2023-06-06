<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\Room;
use App\models\User;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\JsonController;

class BattleController extends Controller
{
    //石がないとき
    const NO_STONE = '0';
    //黒石
    const BLACK_STONE = '1';
    //白石
    const WHITE_STONE = '2';
    //一列の数
    const LINE_NUM = 14;
    //盤面マスの合計(通常は196)
    const SQUAREA_NUM = self::LINE_NUM * self::LINE_NUM;
    //勝利条件の石の数
    const WIN_CONDITION = 3;

    /**
     * ゲーム開始直後の初期値設定
     *
     * @param Request $request
     * @return void
     */
    public function battle_start(Request $request, $room_no, $room_maker_flg){
        // ゲームを開始させる
        $data['start_flg'] = "1";
        
        //手番カウンター
        $data['game_counter'] = "1";
        
        //盤面に「・」をセット
        for($i=1; $i<=self::SQUAREA_NUM; $i++){
            $data["squ_{$i}"] = self::NO_STONE;
        }

        $jsonController = new JsonController;
        $jsonController->update_file($room_no,$data);
        $json_data = $jsonController->get_file($room_no);

        return view('battle')->with([
            'room_maker_flg'=>$room_maker_flg,
            'json_data' => $json_data[0],
        ]);

    }

    /**
     * 手番と盤面を管理する
     *
     * @param Request $request
     * @return void
     */
    public function battle(Request $request){
        
        $room_maker_flg = $request['room_maker_flg'];
        $room_no = $request['room_no'];
        
        //jsonファイルを取得
        $jsonController = new JsonController;
        $json_data = $jsonController->get_file($room_no)[0];

        //ゲームカウンター
        $game_counter = $json_data['game_counter'];

        //手番ごとに白と黒を交互に並べる
        if($game_counter % 2 == 1){
            $json_data["squ_{$request['squ_num']}"] = self::BLACK_STONE;
        }else{
            $json_data["squ_{$request['squ_num']}"] = self::WHITE_STONE;
        }
        $game_counter++;
        $json_data['game_counter'] = $game_counter;

        $json_data = $this->check_win_condition($json_data);

        $jsonController->update_file($room_no,$json_data);
        
        //試合続行
        return view('battle',['room_no'=>$room_no,'room_maker_flg'=>$room_maker_flg])
        ->with(['json_data' => $json_data]);



        // return redirect()->route('battle',['room_no'=>$room_no,'room_maker'=>$request['room_maker']])
        // ->with([
        //     'room_no' => $room_no,
        //     'json_data' => $json_data,
        // ]);
    }

    /**
     * 縦横ナナメで石が5個並んだかチェック
     *
     * @param array $json_data
     * @return array jsondata
     */
    function check_win_condition($json_data){
        // //縦横ナナメで同じ色が5個並んだら勝利
        $win_counter = 0;
        //チェックする色を決める
        if($json_data['game_counter'] % 2 == 1){
            //手番が黒
            $check_color = self::BLACK_STONE;
            $win_player = $json_data['player1_name'];
        }else{
            //手番が白
            $check_color = self::WHITE_STONE;
            $win_player = $json_data['player2_name'];
        }
    
        for($i = 1; $i <= self::LINE_NUM; $i++){
            //縦列をチェック
            for($k = 1; $k <= self::LINE_NUM; $k++){
                $check_squ_num = $i + (($k - 1) * self::LINE_NUM);
                if($json_data["squ_{$check_squ_num}"]== $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        $json_data['win_player'] = $win_player;
                        
                        break 2;
                    }
                }else{
                    $win_counter = 0;
                }
            }
        //     //横列をチェック
        //     for($k = 1; $k <= self::LINE_NUM; $k++){
        //         $check_squ_num = $k + (($i -1) * self::LINE_NUM);
        //         if($json_data[$check_squ_num] == $check_color){
        //             $win_counter++;
        //             if($win_counter == self::WIN_CONDITION){
        //                 return view('battle')->with([
        //                     'player1' => $player1,
        //                     'player2' => $player2,
        //                     'json_data' => $json_data,
        //                     'game_counter' => $game_counter,
        //                     'win_player' => $win_player,
        //                 ]);
        //             }
        //         }else{
        //             $win_counter = 0;
        //         }
        //     }
        //     //ナナメをチェック（↘︎)
        //         for($k = 1; $k <= self::LINE_NUM; $k++){
        //         $check_squ_num = $i + ((self::LINE_NUM  * ($k - 1)) + ($k - 1));
        //         //マス番号が196より大きい場合はスルー
        //         if($check_squ_num > self::SQUAREA_NUM){
        //             continue;
        //         }
        //         if($json_data[$check_squ_num] == $check_color){
        //             $win_counter++;
        //             if($win_counter == self::WIN_CONDITION){
        //                 return view('battle')->with([
        //                     'player1' => $request['player1'],
        //                     'player2' => $request['player2'],
        //                     'json_data' => $json_data,
        //                     'game_counter' => $game_counter,
        //                     'win_player' => $win_player,
        //                 ]);
        //             }
        //         }else{
        //             $win_counter = 0;
        //         }
        //     }
            
        //     // //ナナメをチェック（↙︎)
        //     for($k = 1; $k <= self::LINE_NUM; $k++){
        //         $check_squ_num = $i + ((self::LINE_NUM  * ($k - 1)) - ($k - 1));
        //         //マス番号が0より小さい場合はスルー
        //         if($check_squ_num < 0){
        //             continue;
        //         }
        //         if($json_data[$check_squ_num] == $check_color){
        //             $win_counter++;
        //             if($win_counter == self::WIN_CONDITION){
        //                 return view('battle')->with([
        //                     'player1' => $request['player1'],
        //                     'player2' => $request['player2'],
        //                     'json_data' => $json_data,
        //                     'game_counter' => $game_counter,
        //                     'win_player' => $win_player,
        //                 ]);
        //             }
        //         }else{
        //             $win_counter = 0;
        //         }
        //     }

        }

        return $json_data;
    }


}