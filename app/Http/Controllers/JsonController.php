<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * jsonファイルを扱うコントローラー
 */
class JsonController extends Controller
{
    /**
     * jsonファルを作成する
     *
     * @param int $room_no
     * @param array $data
     * @return void
     */
    public function make_file($room_no,$data){
        $file_path = storage_path("app/json/{$room_no}.json");
        $json_data = json_encode($data,JSON_UNESCAPED_UNICODE);

        file_put_contents($file_path, '[' . $json_data . ']');
    }

    /**
     * ファイルを取得して配列で返す
     *
     * @param int $room_no
     * @return array $data
     */
    public function get_file($room_no){
        $file_path = storage_path("app/json/{$room_no}.json");

        if (File::exists($file_path)) {
            $get_json_data = File::get($file_path);
            //配列に加工
            $data = json_decode($get_json_data,true);
            return $data;
        }
    }
    
    /**
     * 既存ファイルを更新する
     *
     * @param int $room_no
     * @param array $data
     * @return void
     */
    public function update_file($room_no,$data){
        $file_path = storage_path("app/json/{$room_no}.json");
        //jsonデータを取得
        $get_data = $this->get_file($room_no);
        //データを配列に追加
        $merge_data = array_merge($get_data[0],$data);

        // dd($merge_data);

        $update_data = json_encode($merge_data,JSON_UNESCAPED_UNICODE);

        // dd($update_data);

        file_put_contents($file_path, '[' . $update_data . ']');
        
    }

    /**
     * jsonデータを返す（Ajax用）
     *
     * @param Request $request
     * @return json $json_data
     */
    public function return_json(Request $request){
        $room_no = $request['room_no'];
        $file_path = storage_path("app/json/{$room_no}.json");
        if (File::exists($file_path)) {
            $json_data = File::get($file_path);

            // dd($json_data);
    
            return $json_data;
        }
    }

    /**
     * 配列をjsonファイルに変換してストレージに保存する
     *
     * @param int $file_path
     * @param array $data
     * @return void
     */
    // public function put_file($file_path,$data){
    //     $input_json_data = json_encode($data,JSON_UNESCAPED_UNICODE);
    //     file_put_contents($file_path, '[' . $input_json_data . ']');
    // }
}
