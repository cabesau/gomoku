<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('room_no');
            $table->integer('user_id');
            $table->text('comment')->nullable();
            $table->integer('delete_flg');
            $table->integer('exciting_flg');
            $table->integer('player2_id')->nullable();
            $table->integer('start_flg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms',function (Blueprint $table) {
            $table->id();
            $table->integer('room_no');
            $table->integer('user_id');
            $table->text('comment');
            $table->integer('delete_flg');
            $table->integer('exciting_flg');
            $table->timestamps();
            $table->integer('player2_id');
            $table->integer('start_flg');
        });
    }
};
