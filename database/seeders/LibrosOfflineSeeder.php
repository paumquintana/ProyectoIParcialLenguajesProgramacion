<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Libro;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * Seeder LOCAL de libros. Carga una selección curada de libros reales.
 *
 * Los datos (título, autor, género, sinopsis, páginas) están escritos a mano,
 * así que SIEMPRE funciona aunque no haya internet.
 *
 * PORTADAS (orden de prioridad):
 *   1) Si el libro tiene una URL de portada propia (campo coverUrl en los datos),
 *      se guarda esa. Útil para elegir portadas bonitas a mano para la demo.
 *   2) Si no, y hay internet, se busca la portada en Open Library (por título en
 *      inglés) y se guarda el cover_id.
 *   3) Si no hay ninguna, la vista muestra una portada generada con el título.
 */
class LibrosOfflineSeeder extends Seeder
{
    private array $headers = [
        'User-Agent' => 'LecturaApp (paula.martilloq@gmail.com)',
    ];

    /** Si confirmamos que no hay red (varios fallos de conexión seguidos), dejamos de intentar. */
    private bool $sinInternet = false;

    /** Cuenta fallos de conexión consecutivos para distinguir "sin internet" de un rate limit puntual. */
    private int $fallosSeguidos = 0;

    public function run(): void
    {
        // [titulo, nombreAutor, apellidoAutor, genero, anio, paginas, sinopsis, busquedaOpenLibrary, coverUrl]
        // - busquedaOpenLibrary: término en INGLÉS para encontrar la portada en Open Library.
        // - coverUrl: URL de portada propia. Si está, tiene prioridad y NO se llama a la API.
        $datos = [
            ['El Hobbit', 'J.R.R.', 'Tolkien', 'Fantasía', 1937, 310, 'Bilbo Bolsón es arrastrado a una aventura para recuperar un tesoro custodiado por el dragón Smaug.', 'The Hobbit J.R.R. Tolkien', 'https://i.pinimg.com/736x/ec/92/b5/ec92b5f8b9e8380b93e5a2f4598cf65c.jpg'],
            ['El Señor de los Anillos', 'J.R.R.', 'Tolkien', 'Fantasía', 1954, 1178, 'Frodo emprende el viaje para destruir el Anillo Único y salvar la Tierra Media de Sauron.', 'The Lord of the Rings J.R.R. Tolkien', 'https://i.pinimg.com/1200x/66/c4/96/66c4964036f52b38833a94c01873a9ab.jpg'],
            ['Harry Potter y la piedra filosofal', 'J.K.', 'Rowling', 'Fantasía', 1997, 223, 'Un niño descubre que es mago e ingresa al colegio Hogwarts, donde lo espera un destino extraordinario.', "Harry Potter and the Philosopher's Stone Rowling", 'https://i.pinimg.com/1200x/ee/23/df/ee23df3a67f6f27ab8645debc9f6d5e3.jpg'],
            ['Dune', 'Frank', 'Herbert', 'Ciencia Ficción', 1965, 412, 'En el desértico planeta Arrakis, el joven Paul Atreides lucha por el control de la especia más valiosa del universo.', 'Dune Frank Herbert', 'https://i.pinimg.com/736x/ad/b0/94/adb094940402535ab82022f93df76853.jpg'],
            ['Dune: El Mesías', 'Frank', 'Herbert', 'Ciencia Ficción', 1969, 256, 'Doce años después de su ascenso, el emperador Paul Atreides enfrenta las consecuencias de su yihad y una conspiración para derrocarlo.', 'Dune Messiah Frank Herbert', 'https://i.pinimg.com/736x/c1/3d/69/c13d69853e6b1100b3120bde5f8d8b7d.jpg'],
            ['Hijos de Dune', 'Frank', 'Herbert', 'Ciencia Ficción', 1976, 444, 'Los hijos gemelos de Paul Atreides heredan su poder y su carga mientras Arrakis cambia para siempre.', 'Children of Dune Frank Herbert', 'https://i.pinimg.com/736x/45/b1/54/45b1549d0a399961fd2049dba3596c95.jpg'],
            ['Fundación', 'Isaac', 'Asimov', 'Ciencia Ficción', 1951, 244, 'Un matemático predice la caída del Imperio Galáctico y crea una Fundación para preservar el conocimiento.', 'Foundation Isaac Asimov', 'https://i.pinimg.com/736x/8b/de/04/8bde04938343f78b8ac77e3e43fcff9e.jpg'],
            ['1984', 'George', 'Orwell', 'Ciencia Ficción', 1949, 328, 'En un futuro totalitario, Winston Smith desafía al Gran Hermano y al control absoluto del Partido.', 'Nineteen Eighty-Four George Orwell', 'https://i.pinimg.com/1200x/fc/d9/eb/fcd9eb0f4132241fee55a1285431df55.jpg'],
            ['Orgullo y prejuicio', 'Jane', 'Austen', 'Romance', 1813, 432, 'Elizabeth Bennet y el orgulloso señor Darcy aprenden a superar prejuicios y orgullo para encontrarse.', 'Pride and Prejudice Jane Austen', 'https://i.pinimg.com/736x/92/64/a4/9264a4efe574d462d6dccc9f7ae8951e.jpg'],
            ['Bajo la misma estrella', 'John', 'Green', 'Romance', 2012, 313, 'Dos adolescentes con cáncer se enamoran y viven una intensa historia de amor y pérdida.', 'The Fault in Our Stars John Green', 'https://i.pinimg.com/1200x/c2/30/54/c2305483decea22b46be9671b8e431c3.jpg'],
            ['It', 'Stephen', 'King', 'Terror', 1986, 1138, 'Un grupo de amigos enfrenta a una entidad maligna que acecha a los niños del pueblo de Derry.', 'It Stephen King', null],
            ['Drácula', 'Bram', 'Stoker', 'Terror', 1897, 418, 'El conde Drácula viaja desde Transilvania para sembrar el terror, perseguido por el doctor Van Helsing.', 'Dracula Bram Stoker', null],
            ['El nombre de la rosa', 'Umberto', 'Eco', 'Misterio', 1980, 536, 'Una serie de muertes en una abadía medieval es investigada por el fraile Guillermo de Baskerville.', 'The Name of the Rose Umberto Eco', null],
            ['Asesinato en el Orient Express', 'Agatha', 'Christie', 'Misterio', 1934, 256, 'El detective Hércules Poirot resuelve un asesinato a bordo de un tren detenido por la nieve.', 'Murder on the Orient Express Agatha Christie', null],
            ['El código Da Vinci', 'Dan', 'Brown', 'Thriller', 2003, 489, 'El profesor Robert Langdon descifra enigmas ocultos en el arte para resolver un asesinato en el Louvre.', 'The Da Vinci Code Dan Brown', null],
            ['Perdida', 'Gillian', 'Flynn', 'Thriller', 2012, 422, 'La desaparición de Amy Dunne destapa los secretos de un matrimonio lleno de mentiras y manipulación.', 'Gone Girl Gillian Flynn', null],
            ['Veinte poemas de amor y una canción desesperada', 'Pablo', 'Neruda', 'Poesía', 1924, 64, 'Una de las obras poéticas más célebres en español sobre el amor, el deseo y la melancolía.', 'Twenty Love Poems and a Song of Despair Neruda', null],
            ['Hojas de hierba', 'Walt', 'Whitman', 'Poesía', 1855, 384, 'Colección poética que celebra la naturaleza, el individuo y la democracia con verso libre.', 'Leaves of Grass Walt Whitman', null],
            ['Los pilares de la Tierra', 'Ken', 'Follett', 'Ficción Histórica', 1989, 1024, 'La construcción de una catedral en la Inglaterra medieval entrelaza las vidas de varios personajes.', 'The Pillars of the Earth Ken Follett', null],
            ['El médico', 'Noah', 'Gordon', 'Ficción Histórica', 1986, 663, 'Un joven del siglo XI viaja por el mundo para estudiar medicina junto al gran maestro Avicena.', 'The Physician Noah Gordon', null],
        ];

        $conPortada = 0;

        foreach ($datos as [$titulo, $nombre, $apellido, $generoNombre, $anio, $paginas, $sinopsis, $busqueda, $coverUrl]) {
            $autor = Autor::firstOrCreate(
                ['nombre' => $nombre, 'apellido' => $apellido]
            );

            $genero = Genero::firstOrCreate(['nombre' => $generoNombre]);

            // 1) Portada propia (URL elegida a mano). 2) Si no, buscar en Open Library.
            $coverId = $coverUrl ? null : $this->buscarCoverId($busqueda);
            if ($coverUrl || $coverId) {
                $conPortada++;
            }

            $libro = Libro::firstOrCreate(
                ['titulo' => $titulo],
                [
                    'autor_id'         => $autor->id,
                    'anio_publicacion' => $anio,
                    'total_paginas'    => $paginas,
                    'sinopsis'         => $sinopsis,
                    'cover_id'         => $coverId,
                    'cover_url'        => $coverUrl,
                ]
            );

            $libro->generos()->syncWithoutDetaching([$genero->id]);
        }

        $total = count($datos);
        $this->command->info("LibrosOfflineSeeder: {$total} libros cargados ({$conPortada}/{$total} con portada).");
    }

    /**
     * Busca el id de portada (cover_i) en Open Library con un término preciso.
     * Recorre los primeros resultados y devuelve el primero que tenga portada.
     *
     * Es resistente al "rate limit" de Open Library: reintenta hasta 4 veces con
     * pausa, y un fallo puntual NO aborta el resto (solo se asume que no hay
     * internet tras varios fallos de conexión seguidos). Siempre pausa entre
     * llamadas para no saturar la API.
     */
    private function buscarCoverId(string $busqueda): ?int
    {
        if ($this->sinInternet) {
            return null;
        }

        // Pausa entre cada libro: espacia las llamadas y evita el rate limit.
        usleep(700000); // 0.7s

        try {
            $resp = Http::withHeaders($this->headers)
                ->timeout(15)
                ->retry(4, 2000, throw: false) // 4 intentos, 2s entre cada uno
                ->get('https://openlibrary.org/search.json', [
                    'q'      => $busqueda,
                    'fields' => 'cover_i',
                    'limit'  => 5,
                ]);

            if (! $resp->successful()) {
                // La API respondió pero con error (p. ej. rate limit). No abortamos:
                // saltamos este libro y seguimos con los demás.
                return null;
            }

            $this->fallosSeguidos = 0;

            foreach ($resp->json('docs') ?? [] as $doc) {
                if (! empty($doc['cover_i'])) {
                    return (int) $doc['cover_i'];
                }
            }

            return null; // sin portada para este título: se usará la generada
        } catch (ConnectionException $e) {
            // Sin red. Si pasa varias veces seguidas, asumimos offline y paramos.
            if (++$this->fallosSeguidos >= 3) {
                $this->sinInternet = true;
            }
            return null;
        } catch (\Throwable $e) {
            return null; // cualquier otro error: saltar este libro, no abortar todo
        }
    }
}
