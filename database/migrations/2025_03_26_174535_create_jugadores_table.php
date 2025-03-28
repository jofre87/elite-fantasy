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
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id(); // INT PRIMARY KEY
            $table->string('nombre', 100);
            $table->string('posicion', 50);
            $table->foreignId('equipo_id')->nullable()->constrained('equipos')->onDelete('set null');
            $table->string('imagen', 255)->nullable();
            $table->decimal('ratio', 10, 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};
