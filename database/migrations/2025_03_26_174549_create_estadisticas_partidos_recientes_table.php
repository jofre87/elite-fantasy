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
        Schema::create('estadisticas_partidos_recientes', function (Blueprint $table) {
            $table->id(); // INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->enum('rango', ['1 partido', '3 partidos', '5 partidos']);
            $table->integer('puntos');
            $table->integer('partidos_jugados');
            $table->decimal('media', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadisticas_partidos_recientes');
    }
};
