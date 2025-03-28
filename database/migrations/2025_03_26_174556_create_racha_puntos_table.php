<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('racha_puntos', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->integer('partido'); // Número de partido en la racha
            $table->integer('puntos');
            $table->enum('categoria', ['low', 'medium', 'high', 'very-high', 'zero']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racha_puntos');
    }
};
