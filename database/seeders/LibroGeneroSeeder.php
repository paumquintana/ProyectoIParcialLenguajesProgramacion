<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Genero;
use App\Models\Autor;
use App\Models\Libro;

class LibroGeneroSeeder extends Seeder
{
    /**
     * Cabecera que pide Open Library para identificar a la app.
     */
    private array $headers = [
        'User-Agent' => 'LecturaApp (paula.martilloq@gmail.com)',
    ];

    public function run(): void
    {
        // 1. Lista curada de géneros (son "subjects" de Open Library)
        $generos = [
            'fantasy', 'science_fiction', 'romance', 'horror',
            'mystery', 'thriller', 'poetry', 'historical_fiction',
        ];

        foreach ($generos as $subject) {
            // 2. Subjects API: trae la LISTA de obras del género.
            //    OJO: este endpoint NO trae sinopsis, ISBN ni páginas.
            $respuesta = $this->obtener("https://openlibrary.org/subjects/{$subject}.json", [
                'limit' => 20,
            ]);

            if (!$respuesta) {
                $this->command->warn("No se pudo traer el género: {$subject}");
                continue;
            }

            $data = $respuesta->json();

            // 3. Guardar el género con el nombre bonito que da la API.
            $genero = Genero::firstOrCreate(['nombre' => $data['name'] ?? $subject]);

            // 4. Recorrer los libros (works) de ese género.
            foreach ($data['works'] ?? [] as $work) {
                // 4a. Autor: tomamos el primero; si no hay, saltamos el libro.
                $autorData = $work['authors'][0] ?? null;
                if (!$autorData) {
                    continue;
                }

                [$nombre, $apellido] = $this->partirNombre($autorData['name']);

                $autor = Autor::firstOrCreate(
                    ['ol_key' => $autorData['key']],
                    ['nombre' => $nombre, 'apellido' => $apellido]
                );

                // 4b. ¿Ya existe el libro? Si sí, no repetimos las llamadas extra.
                $libro = Libro::where('ol_key', $work['key'])->first();

                if (!$libro) {
                    // 4c. Sinopsis -> Works API (1 llamada extra por libro).
                    $sinopsis = $this->traerSinopsis($work['key']);

                    // 4d. ISBN + páginas -> Editions API (1 llamada extra por libro).
                    [$isbn, $totalPaginas] = $this->traerIsbnYPaginas($work['key']);

                    $libro = Libro::create([
                        'titulo'           => $work['title'],
                        'autor_id'         => $autor->id,
                        'ol_key'           => $work['key'],
                        'anio_publicacion' => $work['first_publish_year'] ?? null,
                        'sinopsis'         => $sinopsis,
                        'isbn'             => $isbn,
                        'total_paginas'    => $totalPaginas,
                        'cover_id'         => $work['cover_id'] ?? null,
                    ]);
                }

                // 4e. Conectar libro ↔ género sin crear duplicados.
                $libro->generos()->syncWithoutDetaching([$genero->id]);
            }

            $this->command->info("Género '{$genero->nombre}' cargado.");

            // 5. Pausa para respetar el límite de Open Library.
            sleep(1);
        }
    }

    /**
     * Hace una petición GET a Open Library de forma segura:
     * - espera hasta 20s por respuesta,
     * - reintenta hasta 3 veces si falla (con 2s de pausa),
     * - si aun así falla, devuelve null en vez de tumbar todo el seeder.
     */
    private function obtener(string $url, array $params = [])
    {
        try {
            $resp = Http::withHeaders($this->headers)
                ->timeout(20)
                ->retry(3, 2000)
                ->get($url, $params);

            return $resp->successful() ? $resp : null;
        } catch (\Throwable $e) {
            $this->command->warn("Petición fallida: {$url}");
            return null;
        }
    }

    /**
     * Parte un nombre completo en [nombre, apellido].
     * Heurística simple: el último token es el apellido, el resto el nombre.
     * No es perfecta con nombres compuestos, pero cubre la mayoría de casos.
     */
    private function partirNombre(string $completo): array
    {
        $partes = preg_split('/\s+/', trim($completo));

        if (count($partes) <= 1) {
            return [$completo, null];
        }

        $apellido = array_pop($partes);
        $nombre = implode(' ', $partes);

        return [$nombre, $apellido];
    }

    /**
     * Trae la descripción (sinopsis) desde la Works API.
     * El campo "description" a veces es string y a veces es {type, value}.
     */
    private function traerSinopsis(string $workKey): ?string
    {
        $resp = $this->obtener("https://openlibrary.org{$workKey}.json");

        usleep(300000); // 0.3s entre llamadas

        if (!$resp) {
            return null;
        }

        $desc = $resp->json('description');

        if (is_array($desc)) {
            $desc = $desc['value'] ?? null;
        }

        return $desc ? trim($desc) : null;
    }

    /**
     * Trae [isbn, total_paginas] desde la Editions API.
     * Prefiere una edición en inglés que tenga número de páginas;
     * usa fallbacks si no encuentra la combinación ideal.
     */
    private function traerIsbnYPaginas(string $workKey): array
    {
        $resp = $this->obtener("https://openlibrary.org{$workKey}/editions.json", ['limit' => 100]);

        usleep(300000); // 0.3s entre llamadas

        if (!$resp) {
            return [null, null];
        }

        $entries = $resp->json('entries') ?? [];

        $isbn = null;
        $paginas = null;
        // Fallbacks por si ninguna edición tiene ambos campos a la vez.
        $isbnFallback = null;
        $paginasFallback = null;

        foreach ($entries as $ed) {
            $esIngles = collect($ed['languages'] ?? [])
                ->contains(fn ($l) => ($l['key'] ?? '') === '/languages/eng');

            $isbnEd = $ed['isbn_13'][0] ?? $ed['isbn_10'][0] ?? null;
            $paginasEd = $ed['number_of_pages'] ?? null;

            // Guardar el primer valor que veamos como respaldo.
            $isbnFallback ??= $isbnEd;
            $paginasFallback ??= $paginasEd;

            // Caso ideal: edición en inglés con ISBN y páginas.
            if ($esIngles && $isbnEd && $paginasEd) {
                return [$isbnEd, $paginasEd];
            }

            // Ir completando con la primera edición en inglés que tenga cada dato.
            if ($esIngles) {
                $isbn ??= $isbnEd;
                $paginas ??= $paginasEd;
            }
        }

        return [
            $isbn ?? $isbnFallback,
            $paginas ?? $paginasFallback,
        ];
    }
}
