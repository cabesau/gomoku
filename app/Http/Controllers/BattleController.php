<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class BattleController extends Controller
{
    //石がない
    const NO_STONE = '0';
    //白石
    const WHITE_STONE = '1';
    //黒石
    const BLACK_STONE = '2';
    //一列の数
    const LINE_NUM = 14;
    //盤面マスの合計(通常は196)
    const SQUAREA_NUM = self::LINE_NUM * self::LINE_NUM;
    //勝利条件の石の数
    const WIN_CONDITION = 3;


    public function top(Request $request){

        //手番カウンター
        $game_counter = 1;

        $squ_arr = array();
        for($i=1; $i<=self::SQUAREA_NUM; $i++){
            $squ_arr += ["{$i}" => self::NO_STONE];
        }
        
        if(isEmpty($request['player1'])){
            $player1 = "プレイヤー１";
        }
        if(isEmpty($request['player2'])){
            $player2 = "プレイヤー２";
        }
        return view('battle')->with([
            'player1' => $player1,
            'player2' => $player2,
            'squ_arr' => $squ_arr,
            'game_counter' => $game_counter,
        ]);

    }

    public function battle(Request $request){
        //盤面を記憶する配列
        $squ_arr = array();
        for($i=1; $i<=self::SQUAREA_NUM; $i++){
            $squ_arr[$i] = $request[$i];
        }
        
        //手番ごとに白と黒を交互に並べる
        $game_counter = $request['game_counter'];
        if($game_counter % 2 == 1){
            $squ_arr[$request['btn_num']] = self::WHITE_STONE;
        }else{
            $squ_arr[$request['btn_num']] = self::BLACK_STONE;
        }
        $game_counter++;
        
        //縦横ナナメで同じ色が5個並んだら勝利
        $win_counter = 0;
        //チェックする色を決める
        if($game_counter % 2 == 0){
            //手番が白
            $check_color = self::WHITE_STONE;
            $win_player = $request['player1'];
        }else{
            //手番が黒
            $check_color = self::BLACK_STONE;
            $win_player = $request['player2'];
        }

        for($i = 1; $i <= self::LINE_NUM; $i++){
            //縦列をチェック
            for($k = 1; $k <= self::LINE_NUM; $k++){
                $check_squ_num = $i + (($k - 1) * self::LINE_NUM);
                if($squ_arr[$check_squ_num] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        return view('battle')->with([
                            'player1' => $request['player1'],
                            'player2' => $request['player2'],
                            'squ_arr' => $squ_arr,
                            'game_counter' => $game_counter,
                            'win_player' => $win_player,
                        ]);
                    }
                }else{
                    $win_counter = 0;
                }
            }
            //横列をチェック
            for($k = 1; $k <= self::LINE_NUM; $k++){
                $check_squ_num = $k + (($i -1) * self::LINE_NUM);
                if($squ_arr[$check_squ_num] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        return view('battle')->with([
                            'player1' => $request['player1'],
                            'player2' => $request['player2'],
                            'squ_arr' => $squ_arr,
                            'game_counter' => $game_counter,
                            'win_player' => $win_player,
                        ]);
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
                if($squ_arr[$check_squ_num] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        return view('battle')->with([
                            'player1' => $request['player1'],
                            'player2' => $request['player2'],
                            'squ_arr' => $squ_arr,
                            'game_counter' => $game_counter,
                            'win_player' => $win_player,
                        ]);
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
                if($squ_arr[$check_squ_num] == $check_color){
                    $win_counter++;
                    if($win_counter == self::WIN_CONDITION){
                        return view('battle')->with([
                            'player1' => $request['player1'],
                            'player2' => $request['player2'],
                            'squ_arr' => $squ_arr,
                            'game_counter' => $game_counter,
                            'win_player' => $win_player,
                        ]);
                    }
                }else{
                    $win_counter = 0;
                }
            }
        }
    
        //試合続行
        return view('battle')->with([
            'player1' => $request['player1'],
            'player2' => $request['player2'],
            'squ_arr' => $squ_arr,
            'game_counter' => $game_counter,
        ]);
    }
}