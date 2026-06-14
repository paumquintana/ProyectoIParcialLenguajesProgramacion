<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('lector_libro', function (Blueprint $table) {
        $table->id();                                              // idLectorLibro propio
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');
        $table->enum('estado', ['por_leer', 'leyendo', 'terminado'])->default('por_leer');
        $table->integer('paginas_leidas')->default(0);
        $table->date('fecha_comienzo')->nullable();
        $table->date('fecha_fin')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lector_libro');
    }
};
