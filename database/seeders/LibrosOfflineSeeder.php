<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Libro;
use Illuminate\Database\Seeder;

/**
 * Seeder LOCAL de libros (no necesita internet).
 * Úsalo cuando Open Library no responde. Carga una selección curada
 * de libros reales repartidos en varios géneros.
 */
class LibrosOfflineSeeder extends Seeder
{
    public function run(): void
    {
        // [titulo, nombreAutor, apellidoAutor, genero, anio, paginas, sinopsis]
        $datos = [
            ['El Hobbit', 'J.R.R.', 'Tolkien', 'Fantasía', 1937, 310, 'Bilbo Bolsón es arrastrado a una aventura para recuperar un tesoro custodiado por el dragón Smaug.'],
            ['El Señor de los Anillos', 'J.R.R.', 'Tolkien', 'Fantasía', 1954, 1178, 'Frodo emprende el viaje para destruir el Anillo Único y salvar la Tierra Media de Sauron.'],
            ['Harry Potter y la piedra filosofal', 'J.K.', 'Rowling', 'Fantasía', 1997, 223, 'Un niño descubre que es mago e ingresa al colegio Hogwarts, donde lo espera un destino extraordinario.'],
            ['Dune', 'Frank', 'Herbert', 'Ciencia Ficción', 1965, 412, 'En el desértico planeta Arrakis, el joven Paul Atreides lucha por el control de la especia más valiosa del universo.'],
            ['Fundación', 'Isaac', 'Asimov', 'Ciencia Ficción', 1951, 244, 'Un matemático predice la caída del Imperio Galáctico y crea una Fundación para preservar el conocimiento.'],
            ['1984', 'George', 'Orwell', 'Ciencia Ficción', 1949, 328, 'En un futuro totalitario, Winston Smith desafía al Gran Hermano y al control absoluto del Partido.'],
            ['Orgullo y prejuicio', 'Jane', 'Austen', 'Romance', 1813, 432, 'Elizabeth Bennet y el orgulloso señor Darcy aprenden a superar prejuicios y orgullo para encontrarse.'],
            ['Bajo la misma estrella', 'John', 'Green', 'Romance', 2012, 313, 'Dos adolescentes con cáncer se enamoran y viven una intensa historia de amor y pérdida.'],
            ['It', 'Stephen', 'King', 'Terror', 1986, 1138, 'Un grupo de amigos enfrenta a una entidad maligna que acecha a los niños del pueblo de Derry.'],
            ['Drácula', 'Bram', 'Stoker', 'Terror', 1897, 418, 'El conde Drácula viaja desde Transilvania para sembrar el terror, perseguido por el doctor Van Helsing.'],
            ['El nombre de la rosa', 'Umberto', 'Eco', 'Misterio', 1980, 536, 'Una serie de muertes en una abadía medieval es investigada por el fraile Guillermo de Baskerville.'],
            ['Asesinato en el Orient Express', 'Agatha', 'Christie', 'Misterio', 1934, 256, 'El detective Hércules Poirot resuelve un asesinato a bordo de un tren detenido por la nieve.'],
            ['El código Da Vinci', 'Dan', 'Brown', 'Thriller', 2003, 489, 'El profesor Robert Langdon descifra enigmas ocultos en el arte para resolver un asesinato en el Louvre.'],
            ['Perdida', 'Gillian', 'Flynn', 'Thriller', 2012, 422, 'La desaparición de Amy Dunne destapa los secretos de un matrimonio lleno de mentiras y manipulación.'],
            ['Veinte poemas de amor y una canción desesperada', 'Pablo', 'Neruda', 'Poesía', 1924, 64, 'Una de las obras poéticas más célebres en español sobre el amor, el deseo y la melancolía.'],
            ['Hojas de hierba', 'Walt', 'Whitman', 'Poesía', 1855, 384, 'Colección poética que celebra la naturaleza, el individuo y la democracia con verso libre.'],
            ['Los pilares de la Tierra', 'Ken', 'Follett', 'Ficción Histórica', 1989, 1024, 'La construcción de una catedral en la Inglaterra medieval entrelaza las vidas de varios personajes.'],
            ['El médico', 'Noah', 'Gordon', 'Ficción Histórica', 1986, 663, 'Un joven del siglo XI viaja por el mundo para estudiar medicina junto al gran maestro Avicena.'],
        ];

        foreach ($datos as [$titulo, $nombre, $apellido, $generoNombre, $anio, $paginas, $sinopsis]) {
            $autor = Autor::firstOrCreate(
                ['nombre' => $nombre, 'apellido' => $apellido]
            );

            $genero = Genero::firstOrCreate(['nombre' => $generoNombre]);

            $libro = Libro::firstOrCreate(
                ['titulo' => $titulo],
                [
                    'autor_id'         => $autor->id,
                    'anio_publicacion' => $anio,
                    'total_paginas'    => $paginas,
                    'sinopsis'         => $sinopsis,
                ]
            );

            $libro->generos()->syncWithoutDetaching([$genero->id]);
        }

        $this->command->info('LibrosOfflineSeeder: ' . count($datos) . ' libros cargados (sin internet).');
    }
}
