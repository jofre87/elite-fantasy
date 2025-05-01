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

            // Foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jugador_id');
            $table->string('liga_id'); // Ahora string para coincidir con ligas.id

            $table->dateTime('comprado_en');
            $table->timestamps();

            // Constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jugador_id')->references('id')->on('jugadores')->onDelete('cascade');
            $table->foreign('liga_id')->references('id')->on('ligas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jugador_user_liga');
    }
}
