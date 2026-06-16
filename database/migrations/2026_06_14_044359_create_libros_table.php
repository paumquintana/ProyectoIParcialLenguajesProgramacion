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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('autor_id')->constrained('autores')->onDelete('cascade');
            $table->string('isbn')->nullable();
            $table->integer('total_paginas')->nullable();
            $table->string('ol_key')->nullable();
            $table->text('sinopsis')->nullable();
            $table->integer('anio_publicacion')->nullable();
            $table->unsignedBigInteger('cover_id')->nullable(); // id de portada en Open Library
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
