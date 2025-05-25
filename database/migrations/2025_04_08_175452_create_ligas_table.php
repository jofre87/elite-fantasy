<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigasTable extends Migration
{
    public function up()
    {
        Schema::create('ligas', function (Blueprint $table) {
            $table->string('id')->primary(); // ID personalizado tipo 'ABC-123'
            $table->string('nombre', 100);
            $table->string('password'); // Campo para pruebas, sin cifrado
            $table->decimal('saldo_inicial', 15, 2)->default(0); // Nuevo campo para saldo inicial
            $table->boolean('jornada_activa')->default(false); // Columna para indicar si la jornada está activa
            $table->foreignId('administrador_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ligas');
    }
}
