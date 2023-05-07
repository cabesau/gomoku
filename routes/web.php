<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\RoomController;

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

//メモ
//postで何も送らない場合→postにしておけば自動でpostデータは送付される

Route::get('/top',[TopController::class,'top'])->name('top');

//getはコントローラーからコントローラーに遷移(?)したい場合に使う。
Route::get('/game',[BattleController::class,'top'])->name('game');
Route::post('/game',[BattleController::class,'top'])->name('game');

Route::post('battle',[BattleController::class,'battle']);

Route::post('wait',[TopController::class,'wait']);

Route::get('/check_players_in_room', [RoomController::class,'check_players_in_room']);

Route::post('/game_start',[BattleController::class,'game_start']);