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
            $table->string('delete_flg');
            $table->string('exciting_flg');
            $table->integer('opponent_user_id')->nullable();
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
            $table->string('delete_flg');
            $table->string('exciting_flg');
            $table->timestamps();
            $table->integer('opponent_user_id');
        });
    }
};
