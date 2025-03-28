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
        Schema::create('estadisticas_temporada', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->integer('puntos_totales');
            $table->decimal('media_puntos', 5, 2);
            $table->integer('partidos_jugados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas_temporada');
    }
};
