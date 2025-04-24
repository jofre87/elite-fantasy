<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJugadorUserLigaTable extends Migration
{
    public function up()
    {
        Schema::create('jugador_user_liga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->foreignId('liga_id')->constrained('ligas')->onDelete('cascade');
            $table->decimal('comprado_en', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jugador_user_liga');
    }
}
