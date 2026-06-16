<?php

namespace Database\Seeders;

use App\Models\Grupo;
use App\Models\LectorLibro;
use App\Models\Libro;
use App\Models\Post;
use App\Models\Resenia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Datos de demostración para poder probar la app de inmediato:
 * un usuario demo con libros en progreso, grupos y posts.
 *
 * Se ejecuta DESPUÉS de LibroGeneroSeeder (necesita libros cargados).
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Grupos de lectura ----
        $grupos = [
            ['nombre' => 'Club de Fantasía',      'descripcion' => 'Para los amantes de mundos mágicos y aventuras épicas.'],
            ['nombre' => 'Ciencia Ficción Hoy',   'descripcion' => 'Debatimos sobre futuros posibles y tecnología.'],
            ['nombre' => 'Clásicos de siempre',   'descripcion' => 'Releyendo las grandes obras de la literatura.'],
        ];
        foreach ($grupos as $g) {
            Grupo::firstOrCreate(['nombre' => $g['nombre']], $g);
        }

        // ---- Usuario demo ----
        $demo = User::firstOrCreate(
            ['email' => 'demo@demo.com'],
            [
                'alias'            => 'demo',
                'nombre'           => 'Daniel',
                'apellido'         => 'Demo',
                'fecha_nacimiento' => '2002-05-10',
                'password'         => Hash::make('password'),
            ]
        );

        // ---- Libros en la biblioteca del usuario demo ----
        $libros = Libro::take(6)->get();
        $estados = ['leyendo', 'leyendo', 'por_leer', 'por_leer', 'terminado', 'terminado'];

        foreach ($libros as $i => $libro) {
            $estado = $estados[$i] ?? 'por_leer';
            $paginas = 0;
            if ($estado === 'leyendo' && $libro->total_paginas) {
                $paginas = (int) round($libro->total_paginas * 0.4);
            } elseif ($estado === 'terminado') {
                $paginas = $libro->total_paginas ?? 0;
            }

            $lectura = LectorLibro::firstOrCreate(
                ['user_id' => $demo->id, 'libro_id' => $libro->id],
                [
                    'estado'         => $estado,
                    'paginas_leidas' => $paginas,
                    'fecha_comienzo' => $estado !== 'por_leer' ? now()->subDays(20) : null,
                    'fecha_fin'      => $estado === 'terminado' ? now()->subDays(2) : null,
                ]
            );

            // Una reseña para los libros terminados.
            if ($estado === 'terminado') {
                Resenia::firstOrCreate(
                    ['lector_libro_id' => $lectura->id],
                    ['puntuacion' => 5, 'comentario' => '¡Me encantó este libro!', 'fecha' => now()->toDateString()]
                );
            }
        }

        // ---- Unir al usuario demo a un par de grupos + posts ----
        $grupoFantasia = Grupo::where('nombre', 'Club de Fantasía')->first();
        if ($grupoFantasia) {
            $demo->grupos()->syncWithoutDetaching([$grupoFantasia->id]);
            Post::firstOrCreate([
                'grupo_id' => $grupoFantasia->id,
                'user_id'  => $demo->id,
                'contenido' => '¡Hola a todos! ¿Qué están leyendo esta semana?',
            ]);
        }
    }
}
