<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function user(){
        
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'id',
        'room_no',
        'user_id',
        'comment',
        'delete_flg',
        'exciting_flg',
        'player2_id',
        'start_flg',
    ];

}
