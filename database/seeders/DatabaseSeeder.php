<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario de prueba de Paula.
        User::factory()->create([
            'nombre' => 'Test',
            'apellido' => 'User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            // ===== ELIGE UNA FUENTE DE LIBROS (deja solo UNA activa) =====
            //
            // OPCIÓN A — LOCAL (por defecto): 18 libros curados. Carga rápida.
            //            Si hay internet, baja la PORTADA real de cada libro;
            //            si no, usa una portada generada con el título. Siempre funciona.
            LibrosOfflineSeeder::class,
            //
            // OPCIÓN B — API Open Library: catálogo más grande bajado por género (~6 min,
            //            requiere internet). Para usarla: comenta la línea de arriba
            //            (OPCIÓN A) y descomenta la línea de abajo (OPCIÓN B).
            // LibroGeneroSeeder::class,
            //
            // =============================================================

            DemoSeeder::class,        // usuario demo + biblioteca, grupos y posts
            DemoDanielSeeder::class,  // usuario daniel123 leyendo la saga de Dune
        ]);
    }
}
