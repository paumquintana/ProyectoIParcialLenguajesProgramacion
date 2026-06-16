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

class DemoDanielSeeder extends Seeder
{
    /**
     * Crea un usuario demo con datos de ejemplo:
     * libros en progreso, biblioteca, reseñas, grupos y posts.
     * Se ejecuta DESPUÉS de LibroGeneroSeeder (necesita libros ya cargados).
     */
    public function run(): void
    {
        // 1. Usuario demo (alias: demo / demo@demo.com / password)
        $demo = User::firstOrCreate(
            ['email' => 'demo@demo.com'],
            [
                'alias'            => 'demo',
                'nombre'           => 'Demo',
                'apellido'         => 'Lector',
                'fecha_nacimiento' => '2000-01-01',
                'password'         => Hash::make('password'),
            ]
        );

        // 2. Libros de ejemplo (si el catálogo está vacío, no hacemos nada).
        $libros = Libro::inRandomOrder()->take(6)->get();
        if ($libros->isEmpty()) {
            $this->command->warn('No hay libros cargados; se omite la biblioteca demo.');
            return;
        }

        // Estados a repartir entre los libros.
        $plan = ['leyendo', 'leyendo', 'por_leer', 'por_leer', 'terminado', 'terminado'];

        foreach ($libros as $i => $libro) {
            $estado = $plan[$i] ?? 'por_leer';
            $total = $libro->total_paginas ?: 300;

            $paginas = match ($estado) {
                'leyendo'   => (int) round($total * 0.4),
                'terminado' => $total,
                default     => 0,
            };

            $lectura = LectorLibro::firstOrCreate(
                ['user_id' => $demo->id, 'libro_id' => $libro->id],
                [
                    'estado'         => $estado,
                    'paginas_leidas' => $paginas,
                    'fecha_comienzo' => $estado !== 'por_leer' ? now()->subDays(20)->toDateString() : null,
                    'fecha_fin'      => $estado === 'terminado' ? now()->subDays(2)->toDateString() : null,
                ]
            );

            // Reseña para los libros terminados.
            if ($estado === 'terminado') {
                Resenia::firstOrCreate(
                    ['lector_libro_id' => $lectura->id],
                    [
                        'puntuacion' => rand(4, 5),
                        'comentario' => 'Una lectura que disfruté mucho, totalmente recomendada.',
                        'fecha'      => now()->subDays(1)->toDateString(),
                    ]
                );
            }
        }

        // 3. Grupos de lectura + foro con posts.
        $grupos = [
            ['nombre' => 'Club de Fantasía', 'descripcion' => 'Para amantes de mundos mágicos y épicos.'],
            ['nombre' => 'Ciencia Ficción ETERNA', 'descripcion' => 'Debatimos el futuro, el espacio y la tecnología.'],
        ];

        foreach ($grupos as $g) {
            $grupo = Grupo::firstOrCreate(['nombre' => $g['nombre']], ['descripcion' => $g['descripcion']]);
            $grupo->lectores()->syncWithoutDetaching([$demo->id]);

            Post::firstOrCreate([
                'grupo_id'  => $grupo->id,
                'user_id'   => $demo->id,
                'contenido' => '¡Hola a todos! ¿Qué están leyendo esta semana?',
            ]);
        }

        $this->command->info('Usuario demo creado: demo@demo.com / password');
    }
}
