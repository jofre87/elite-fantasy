<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposUsuarioJornadaTable extends Migration
{
    public function up()
    {
        Schema::create('equipos_usuario_jornada', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->string('liga_id');
            $table->unsignedBigInteger('jornada_id');
            $table->unsignedBigInteger('jugador_id');

            $table->integer('puntos')->default(0);
            $table->string('posicion')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('liga_id')->references('id')->on('ligas')->onDelete('cascade');
            $table->foreign('jugador_id')->references('id')->on('jugadores')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipos_usuario_jornada');
    }
}
