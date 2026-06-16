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
            // Libros locales (NO necesita internet). Confiable para la entrega.
            LibrosOfflineSeeder::class,

            // Si tienes internet y prefieres bajar libros reales de Open Library,
            // comenta la línea de arriba y descomenta la siguiente (~6 min):
            // LibroGeneroSeeder::class,

            DemoSeeder::class,   // usuario demo + biblioteca, grupos y posts
        ]);
    }
}
