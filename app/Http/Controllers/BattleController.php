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
    //勝利条件の石の数を設定
    const WIN_CONDITION = 5;
    //プレイヤー1のターン
    const TURN_PLAYER1 = 1;
    //プレイヤー2のターン
    const TURN_PLAYER2 = 2;

    /**
     * バトル画面に遷移する
     *
     * @param Request $request
     * @return void
     */
    function battle($room_no, $room_maker_flg){
        $jsonController = new JsonController;
        $json_data = $jsonController->get_file($room_no)[0];

        // 初回ならルームを初期化する
        if(empty($json_data['game_counter'])){
            $this->battle_start($room_no,$room_maker_flg);
        }

        return view('/battle')
        ->with(['room_no'=>$room_no,'room_maker_flg'=>$room_maker_flg]);
    }

    /**
     * ゲーム開始直後の初期値設定
     *
     * @param Request $request
     * @return void
     */
    public function battle_start($room_no, $room_maker_flg){
        
        //jsonファイルを取得
        $jsonController = new JsonController;
        $json_data = $jsonController->get_file($room_no)[0];

        // ゲームを開始させる
        $data['start_flg'] = "1";
        //手番カウンター
        $data['game_counter'] = "1";

        if($room_maker_flg == 1){
            //盤面に「・」をセット
            for($i=1; $i<=self::SQUAREA_NUM; $i++){
                $data["squ_{$i}"] = self::NO_STONE;
            }

            //エキサイティングモードの場合
            if($json_data['exciting_flg'] == 1 && $room_maker_flg == 1){
                $exciting_data =$this->exciting_mode();
                $data = array_merge($data,$exciting_data);
            }
        }

        $jsonController = new JsonController;
        $jsonController->update_file($room_no,$data);
        $json_data = $jsonController->get_file($room_no);
    }
    
    /**
     * 場面にランダムで石を3つ置く
     *
     * @param array $data
     * @return arrat $data
     */
    function exciting_mode(){
        //最初の黒石
        $first_black_stone = rand(1,self::SQUAREA_NUM);

        //二番めの黒石
        do{
            $second_black_stone = rand(1,self::SQUAREA_NUM); 
        }while($this->check_initial_position($first_black_stone,$second_black_stone));

        //三番目の黒石
        do{
            $third_black_stone = rand(1,self::SQUAREA_NUM); 
        }while($this->check_initial_position($first_black_stone,$third_black_stone) //一番目と三番目
            || $this->check_initial_position($second_black_stone,$third_black_stone));//二番目と三番目

        do{
            $first_white_stone = rand(1,self::SQUAREA_NUM); 
        }while($first_white_stone == $first_black_stone
            || $first_white_stone == $second_black_stone
            || $first_white_stone == $third_black_stone);

        //二番目の白石
        do{
            $second_white_stone = rand(1,self::SQUAREA_NUM); 
        }while($second_white_stone == $first_black_stone
            || $second_white_stone == $second_black_stone
            || $second_white_stone == $third_black_stone
            || $this->check_initial_position($first_white_stone,$second_white_stone));
            
        //三番目の白石
        do{
            $third_white_stone = rand(1,self::SQUAREA_NUM); 
        }while($third_white_stone == $first_black_stone
            || $third_white_stone == $second_black_stone
            || $third_white_stone == $third_black_stone
            || $this->check_initial_position($first_white_stone,$third_white_stone)
            || $this->check_initial_position($second_white_stone,$third_white_stone));

        $data = [
                "squ_{$first_black_stone}" => 1,
                "squ_{$second_black_stone}" => 1,
                "squ_{$third_black_stone}" => 1,
                "squ_{$first_white_stone}" => 2,
                "squ_{$second_white_stone}" => 2,
                "squ_{$third_white_stone}" => 2,
        ];

        return $data;
    }

    /**
     * 初期位置をチェックする
     *
     * @param int $num1
     * @param int $num2
     * @return bool
     */
    function check_initial_position($num1,$num2){
        //同じはダメ
        if($num1 == $num2){
            return true;
        }

        //タテ
        if($num1 - $num2 == self::LINE_NUM || $num2 - $num2 == self::LINE_NUM){
            return true;
        }

        //ヨコ
        if($num1 - $num2 == 1 || $num2-$num1 == 1){
            // if($num1 % self::LINE_NUM == 0 || $num2 % self::LINE_NUM == 0){
            //     break;
            // }
            return true;
        }

        //ナナメ（↘︎）
        if($num1 - $num2 == self::LINE_NUM +1 || $num1 - $num1 == self::LINE_NUM +1){
            return true;
        }

        //ナナメ（↙︎）
        if($num1 - $num2 == self::LINE_NUM -1 || $num1 - $num1 == self::LINE_NUM -1){
            return true;
        }

    }

    /**
     * 手番と盤面を管理する
     *
     * @param Request $request
     * @return void
     */
    public function battle_cal(Request $request){
        
        $room_maker_flg = $request['room_maker_flg'];
        $room_no = $request['room_no'];
        $squ_num = $request['squ_num'];
        //jsonファイルを取得
        $jsonController = new JsonController;
        $json_data = $jsonController->get_file($room_no)[0];

        //ゲームカウンター
        $game_counter = $json_data['game_counter'];
        $game_counter++;
        $json_data['game_counter'] = $game_counter;

        //手番ごとに白と黒を交互に並べる
        if($json_data['turn_player'] == self::TURN_PLAYER1){
            $json_data["squ_{$squ_num}"] = self::BLACK_STONE;
            $json_data = $this->check_win_condition($json_data);
            $json_data['turn_player'] = self::TURN_PLAYER2;
        }else{
            $json_data["squ_{$squ_num}"] = self::WHITE_STONE;
            $json_data = $this->check_win_condition($json_data);
            $json_data['turn_player'] = self::TURN_PLAYER1;
         }

        $jsonController->update_file($room_no,$json_data);

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
        if($json_data['turn_player'] == self::TURN_PLAYER1){
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
            //横列をチェック
            for($k = 1; $k <= self::LINE_NUM; $k++){
                $check_squ_num = $k + (($i -1) * self::LINE_NUM);
                if($json_data["squ_{$check_squ_num}"] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        $json_data['win_player'] = $win_player;
                        
                        break 2;
                    }
                }else{
                    $win_counter = 0;
                }
            }
            //ナナメをチェック（↘︎)
                for($k = 1; $k <= self::LINE_NUM; $k++){
                $check_squ_num = $i + ((self::LINE_NUM  * ($k - 1)) + ($k - 1));
                //マス番号が196より大きい場合はスルー
                if($check_squ_num > self::SQUAREA_NUM){
                    continue;
                }
                if($json_data["squ_{$check_squ_num}"] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        $json_data['win_player'] = $win_player;
                        
                        break 2;
                    }
                }else{
                    $win_counter = 0;
                }
            }   
            // //ナナメをチェック（↙︎)
            for($k = 1; $k <= self::LINE_NUM; $k++){
                $check_squ_num = $i + ((self::LINE_NUM  * ($k - 1)) - ($k - 1));
                //マス番号が0より小さい場合はスルー
                if($check_squ_num < 0){
                    continue;
                }
                if($json_data["squ_{$check_squ_num}"] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        $json_data['win_player'] = $win_player;
                        
                        break 2;
                    }
                }else{
                    $win_counter = 0;
                }
            }
        }
        return $json_data;
    }   
}