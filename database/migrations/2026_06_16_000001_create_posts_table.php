<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();                                                      // idPost
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // idLector
            $table->text('contenido');
            $table->timestamps();                                             // fecha de creacion
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
