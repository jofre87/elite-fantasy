<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigasTable extends Migration
{
    public function up()
    {
        Schema::create('ligas', function (Blueprint $table) {
            $table->id(); // Crea una columna 'id' como clave primaria
            $table->string('nombre', 100);
            $table->foreignId('administrador_id')->constrained('users')->onDelete('cascade'); // Relación con la tabla 'users'
            $table->timestamps(); // Crea columnas 'created_at' y 'updated_at'
        });
    }

    public function down()
    {
        Schema::dropIfExists('ligas'); // Elimina la tabla si se revierte la migración
    }
}