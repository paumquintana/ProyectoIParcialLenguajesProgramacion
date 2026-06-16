<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega cover_url: una URL de portada propia (p. ej. una imagen elegida a mano).
     * Si está presente, tiene prioridad sobre cover_id (Open Library) en el modelo.
     */
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->string('cover_url')->nullable()->after('cover_id');
        });
    }

    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            $table->dropColumn('cover_url');
        });
    }
};
