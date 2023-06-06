<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\JsonController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
////////トップ画面を制御///////
Route::get('/top',[TopController::class,'top'])->name('top'); //トップ画面に遷移する
Route::post('/top',[TopController::class,'top'])->name('top'); //トップ画面に遷移する

////////ルームを制御///////
Route::get('room/{room_no}/{room_maker_flg}', [RoomController::class, 'room_top'])->name('room'); //ルーム画面遷移する
Route::post('make_room',[TopController::class,'make_room']);//ルーム作成メソッド
Route::post('in_room',[TopController::class,'in_room']); //既存のルームに入る


////////バトルを制御///////
Route::get('/battle_start/{room_no}/{room_maker_flg}',[BattleController::class,'battle_start'])->name('battle_start'); //バトルの初期値を設定する
Route::get('/battle/{room_no}/{room_maker_flg}/{squ_num}',[BattleController::class,'battle'])->name('battle'); //実際のバトルを制御

////////非同期用///////
Route::get('/check_players_in_room', [RoomController::class,'check_players_in_room']); //プレイヤーが二人揃ったか確認
Route::get('/check_game_started',[RoomController::class,'check_game_started']); //ゲームが開始されたか確認
Route::post('/return_json',[JsonController::class,'return_json']); //jsonファイルを返す
Route::get('/update_finish_info',[RoomController::class,'update_finish_info']); //ゲーム終了情報を更新する

////////メモ///////
//getはコントローラーからコントローラーに遷移(?)したい場合に使う。

////////不要？///////
Route::post('room/top',[RoomController::class,'room_top']); //ルーム画面遷移する