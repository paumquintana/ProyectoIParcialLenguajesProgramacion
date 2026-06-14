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
        Schema::create('autores', function (Blueprint $table) {
            $table->id();                          // idAutor automático
            $table->string('nombre');
            $table->string('apellido')->nullable(); // puede ser null
            $table->string('ol_key')->nullable();  // referencia a Open Library
            $table->timestamps();                  // fechas de creación/edición
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores');
    }
};
