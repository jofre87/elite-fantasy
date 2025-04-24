<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigaUserTable extends Migration
{
    public function up()
    {
        Schema::create('liga_user', function (Blueprint $table) {
            $table->id();

            // Cambiado de foreignId() a string() para que coincida con el ID personalizado de ligas
            $table->string('liga_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('saldo', 15, 2);
            $table->timestamps();

            // Claves foráneas
            $table->foreign('liga_id')->references('id')->on('ligas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('liga_user');
    }
}
