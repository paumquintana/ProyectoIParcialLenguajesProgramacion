<?php

namespace Database\Seeders;

use App\Models\LectorLibro;
use App\Models\Libro;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Usuario dummy "daniel123" para la demostración.
 * Tiene en estado "leyendo" toda la saga de Dune.
 *
 * Se ejecuta DESPUÉS de LibrosOfflineSeeder (necesita los libros ya cargados).
 * Como persiste vía seeder, vuelve a aparecer en cada `migrate:fresh --seed`.
 */
class DemoDanielSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuario daniel123.
        $daniel = User::firstOrCreate(
            ['email' => 'daniel123@correo.com'],
            [
                'alias'            => 'daniel123',
                'nombre'           => 'Daniel',
                'apellido'         => 'Vaca',
                'fecha_nacimiento' => '2002-03-15',
                'password'         => Hash::make('123456789'),
            ]
        );

        // 2. Saga de Dune en estado "leyendo", con distinto progreso cada uno.
        //    [titulo => paginasLeidas]  (las páginas totales vienen del propio libro)
        $saga = [
            'Dune'            => 280,  // bien avanzado
            'Dune: El Mesías' => 120,  // a la mitad
            'Hijos de Dune'   => 40,   // recién empezado
        ];

        foreach ($saga as $titulo => $paginasLeidas) {
            $libro = Libro::where('titulo', $titulo)->first();
            if (! $libro) {
                $this->command->warn("DemoDanielSeeder: no se encontró '{$titulo}', se omite.");
                continue;
            }

            LectorLibro::firstOrCreate(
                ['user_id' => $daniel->id, 'libro_id' => $libro->id],
                [
                    'estado'         => 'leyendo',
                    'paginas_leidas' => min($paginasLeidas, $libro->total_paginas ?? $paginasLeidas),
                    'fecha_comienzo' => now()->subDays(10)->toDateString(),
                    'fecha_fin'      => null,
                ]
            );
        }

        $this->command->info('DemoDanielSeeder: usuario daniel123 / 123456789 leyendo la saga Dune.');
    }
}
