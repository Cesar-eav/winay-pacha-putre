<?php

namespace Database\Seeders;

use App\Models\Tema;
use Illuminate\Database\Seeder;

class TemasSeeder extends Seeder
{
    /**
     * Vuelca el contenido real de la tabla `temas` de desarrollo a producción.
     * Idempotente (updateOrCreate por slug): seguro de correr más de una vez.
     *
     * Las imágenes referenciadas aquí deben copiarse aparte a
     * storage/app/public/temas en el servidor (no viajan por git).
     */
    public function run(): void
    {
        $temas = [
            ['slug' => 'cosmovision-aymara', 'categoria' => 'cultura', 'titulo' => 'Cosmovisión aymara', 'cuerpo' => '<p>Texto placeholder sobre la cosmovisión aymara: la relación con la Pachamama, los apus y el territorio altiplánico. Contenido pendiente de revisión con el cliente.</p>', 'orden' => 0, 'imagenes' => [
                ['path' => 'temas/haCE4huMb8qRQb03dy67cF02t34xssZmuRI98pm8.jpg', 'alt' => 'Cosmovisión aymara', 'orden' => 0],
                ['path' => 'temas/KjfrWVgkrR1lXOdnkTO7MabAors1RzEJsohPPfyr.webp', 'alt' => 'Cosmovisión aymara', 'orden' => 1],
            ]],
            ['slug' => 'textiles-y-artesania', 'categoria' => 'cultura', 'titulo' => 'Textiles y artesanía', 'cuerpo' => '<p>Texto placeholder sobre las técnicas textiles y la artesanía local de Putre y sus comunidades.</p>', 'orden' => 1],
            ['slug' => 'lengua-y-tradicion-oral', 'categoria' => 'cultura', 'titulo' => 'Lengua y tradición oral', 'cuerpo' => '<p>Texto placeholder sobre el aymara como lengua viva y la tradición oral de la zona.</p>', 'orden' => 2],
            ['slug' => 'trekking-en-el-altiplano', 'categoria' => 'putre_blog', 'titulo' => 'Trekking en el altiplano', 'cuerpo' => '<p>Texto placeholder sobre rutas de trekking recomendadas cerca de Putre.</p>', 'orden' => 3],
            ['slug' => 'observacion-de-estrellas', 'categoria' => 'putre_blog', 'titulo' => 'Observación de estrellas', 'cuerpo' => '<p>Texto placeholder sobre observación astronómica en el altiplano.</p>', 'orden' => 4],
            ['slug' => 'ferias-y-mercados-locales', 'categoria' => 'putre_blog', 'titulo' => 'Ferias y mercados locales', 'cuerpo' => '<p>Texto placeholder sobre ferias y comercio local en Putre.</p>', 'orden' => 5],
            ['slug' => 'gastronomia-local', 'categoria' => 'putre_blog', 'titulo' => 'Gastronomía local', 'cuerpo' => '<p>Texto placeholder sobre la gastronomía típica de la zona.</p>', 'orden' => 6],
            ['slug' => 'flora-y-fauna-del-altiplano', 'categoria' => 'putre_blog', 'titulo' => 'Flora y fauna del altiplano', 'cuerpo' => '<p>Texto placeholder sobre la flora y fauna del altiplano: vicuñas, vizcachas, flamencos andinos, ñandúes y queñoas. Contenido pendiente de revisión con el cliente.</p>', 'orden' => 7],
            ['slug' => 'viajeros-en-busca-de-naturaleza', 'categoria' => 'publico_objetivo', 'titulo' => 'Viajeros en busca de naturaleza', 'cuerpo' => '<p>Texto placeholder describiendo a quién está orientada la experiencia: amantes de la naturaleza y el trekking.</p>', 'orden' => 8],
            ['slug' => 'interesados-en-cultura-originaria', 'categoria' => 'publico_objetivo', 'titulo' => 'Interesados en cultura originaria', 'cuerpo' => '<p>Texto placeholder describiendo a quién está orientada la experiencia: interesados en la cultura aymara.</p>', 'orden' => 9],
            ['slug' => 'plaza-putre', 'categoria' => 'putre_blog', 'titulo' => 'Plaza de Putre', 'cuerpo' => '<p>El corazón del pueblo, junto a la Iglesia de San Ildefonso —levantada en 1670 y restaurada en 1871, hoy Monumento Nacional—. Sus calles adoquinadas, muros de adobe y rejas de hierro forjado conservan el trazo colonial que hace de Putre una de las puertas de entrada al altiplano chileno.</p><p>Es el punto de encuentro de la vida local: un lugar ideal para sentarse a observar el día a día del pueblo y aclimatarse con calma a los 3.500 metros de altura antes de salir a explorar el Parque Nacional Lauca y sus alrededores.</p>', 'orden' => 9],
            ['slug' => 'juegos-infantiles', 'categoria' => 'putre_blog', 'titulo' => 'Juegos infantiles', 'cuerpo' => '<p>Un espacio de juegos infantiles en Putre, pensado para las familias que visitan o viven en el pueblo. (Placeholder — pendiente de que el cliente confirme la ubicación exacta y los detalles reales de este lugar.)</p>', 'orden' => 10],
            ['slug' => 'vista-volcan-tapaca', 'categoria' => 'putre_blog', 'titulo' => 'Vista al volcán Taapaca', 'cuerpo' => '<p>Conocido también como los Nevados de Putre, el volcán Taapaca se alza al norte del pueblo con sus 5.860 metros de altura y fue un sitio ceremonial inca: en su cumbre se halló una estructura de piedra con una ofrenda ritual.</p><p>Desde varios puntos de Putre, y especialmente desde los senderos en sus faldas, se abren vistas privilegiadas del altiplano y la cordillera —en días despejados incluso se alcanza a ver el mar—. Un atractivo imperdible para quienes buscan fotografías y contacto directo con el paisaje andino.</p>', 'orden' => 11],
            ['slug' => 'puertas-historicas', 'categoria' => 'putre_blog', 'titulo' => 'Puertas históricas de Putre', 'cuerpo' => '<p>Al recorrer las calles empedradas de Putre todavía se conservan puertas y fachadas de fines del siglo XIX, testigos silenciosos de una época en que el pueblo formaba parte del territorio peruano, antes de que la Guerra del Pacífico redefiniera la frontera y la región pasara a soberanía chilena.</p><p>Sus marcos de madera trabajada, rejas de hierro forjado y muros de adobe se mezclan hoy con la vida cotidiana del pueblo, convirtiendo un simple paseo por el casco histórico en un recorrido por la memoria fronteriza del altiplano.</p>', 'orden' => 12],
            ['slug' => 'cementerio-de-putre', 'categoria' => 'putre_blog', 'titulo' => 'Cementerio de Putre', 'cuerpo' => '<p>Forma parte del conjunto religioso declarado Monumento Nacional junto a la Iglesia de San Ildefonso, con su campanario exento, capilla de acceso y muro perimetral de piedra. Un lugar de recogimiento con el altiplano como telón de fondo.</p><p>Sus tumbas y construcciones reflejan la profunda tradición católica arraigada en la cultura aymara del pueblo, y ofrecen una mirada íntima a la historia y las costumbres funerarias de Putre a lo largo de más de un siglo.</p>', 'orden' => 13],
        ];

        foreach ($temas as $data) {
            $tema = Tema::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'categoria' => $data['categoria'],
                    'titulo' => ['es' => $data['titulo']],
                    'cuerpo' => ['es' => $data['cuerpo']],
                    'orden' => $data['orden'],
                    'publicado' => true,
                ]
            );

            foreach ($data['imagenes'] ?? [] as $imagen) {
                $tema->imagenes()->updateOrCreate(
                    ['path' => $imagen['path']],
                    ['alt' => $imagen['alt'], 'orden' => $imagen['orden']]
                );
            }
        }
    }
}
