<?php

namespace Database\Seeders;

use App\Models\Cabana;
use App\Models\Configuracion;
use App\Models\Equipamiento;
use App\Models\Especie;
use App\Models\LugarEntorno;
use App\Models\PaginaNosotros;
use App\Models\ServicioLocal;
use App\Models\Tema;
use Illuminate\Database\Seeder;

class WinayPlaceholderSeeder extends Seeder
{
    /**
     * Contenido placeholder (calidad borrador, no copy final) para revisar
     * estructura y layout mientras el cliente aún no envía textos/fotos reales.
     */
    public function run(): void
    {
        $this->configuracion();
        $this->equipamientos();
        $this->temas();
        $this->cabanas();
        $this->lugaresEntorno();
        $this->especies();
        $this->serviciosLocales();
        $this->paginaNosotros();
    }

    private function configuracion(): void
    {
        Configuracion::set('contacto_direccion', 'Camino a Putre s/n, Putre, Región de Arica y Parinacota');
        Configuracion::set('contacto_telefono', '+56 9 1234 5678');
        Configuracion::set('contacto_whatsapp', '+56 9 1234 5678');
        Configuracion::set('contacto_email', 'contacto@winaypachaputre.cl');
        Configuracion::set('inicio_titulo', 'Wiñay Pacha Putre');
        Configuracion::set('inicio_subtitulo', 'Cabañas en el corazón del altiplano aymara');
        Configuracion::set('inicio_texto', 'Texto de bienvenida placeholder: aquí irá la presentación del proyecto, su vínculo con la cultura aymara y el territorio de Putre. Pendiente de contenido definitivo del cliente.');
        Configuracion::set('redes_instagram', 'https://instagram.com/winaypachaputre');
        Configuracion::set('redes_facebook', 'https://facebook.com/winaypachaputre');
    }

    private function equipamientos(): void
    {
        $cabana = ['Cocina equipada', 'Estufa a leña', 'Agua caliente', 'Wifi', 'Estacionamiento'];
        $habitacion = ['Cama matrimonial', 'Ropa de cama', 'Calefacción', 'Velador'];

        foreach ($cabana as $orden => $nombre) {
            Equipamiento::firstOrCreate(
                ['nombre' => $nombre, 'ambito' => 'cabana'],
                ['icono' => 'check', 'orden' => $orden]
            );
        }

        foreach ($habitacion as $orden => $nombre) {
            Equipamiento::firstOrCreate(
                ['nombre' => $nombre, 'ambito' => 'habitacion'],
                ['icono' => 'check', 'orden' => $orden]
            );
        }
    }

    private function temas(): void
    {
        $temas = [
            ['slug' => 'cosmovision-aymara', 'categoria' => 'cultura', 'titulo' => 'Cosmovisión aymara', 'cuerpo' => '<p>Texto placeholder sobre la cosmovisión aymara: la relación con la Pachamama, los apus y el territorio altiplánico. Contenido pendiente de revisión con el cliente.</p>'],
            ['slug' => 'textiles-y-artesania', 'categoria' => 'cultura', 'titulo' => 'Textiles y artesanía', 'cuerpo' => '<p>Texto placeholder sobre las técnicas textiles y la artesanía local de Putre y sus comunidades.</p>'],
            ['slug' => 'lengua-y-tradicion-oral', 'categoria' => 'cultura', 'titulo' => 'Lengua y tradición oral', 'cuerpo' => '<p>Texto placeholder sobre el aymara como lengua viva y la tradición oral de la zona.</p>'],
            ['slug' => 'trekking-en-el-altiplano', 'categoria' => 'actividad', 'titulo' => 'Trekking en el altiplano', 'cuerpo' => '<p>Texto placeholder sobre rutas de trekking recomendadas cerca de Putre.</p>'],
            ['slug' => 'observacion-de-estrellas', 'categoria' => 'actividad', 'titulo' => 'Observación de estrellas', 'cuerpo' => '<p>Texto placeholder sobre observación astronómica en el altiplano.</p>'],
            ['slug' => 'ferias-y-mercados-locales', 'categoria' => 'vive_local', 'titulo' => 'Ferias y mercados locales', 'cuerpo' => '<p>Texto placeholder sobre ferias y comercio local en Putre.</p>'],
            ['slug' => 'gastronomia-local', 'categoria' => 'vive_local', 'titulo' => 'Gastronomía local', 'cuerpo' => '<p>Texto placeholder sobre la gastronomía típica de la zona.</p>'],
            ['slug' => 'viajeros-en-busca-de-naturaleza', 'categoria' => 'publico_objetivo', 'titulo' => 'Viajeros en busca de naturaleza', 'cuerpo' => '<p>Texto placeholder describiendo a quién está orientada la experiencia: amantes de la naturaleza y el trekking.</p>'],
            ['slug' => 'interesados-en-cultura-originaria', 'categoria' => 'publico_objetivo', 'titulo' => 'Interesados en cultura originaria', 'cuerpo' => '<p>Texto placeholder describiendo a quién está orientada la experiencia: interesados en la cultura aymara.</p>'],
        ];

        foreach ($temas as $orden => $tema) {
            Tema::updateOrCreate(
                ['slug' => $tema['slug']],
                [
                    'categoria' => $tema['categoria'],
                    'titulo' => ['es' => $tema['titulo']],
                    'cuerpo' => ['es' => $tema['cuerpo']],
                    'orden' => $orden,
                    'publicado' => true,
                ]
            );
        }
    }

    private function cabanas(): void
    {
        $cabanas = [
            ['slug' => 'cabana-titicaca', 'nombre' => 'Cabaña Titicaca', 'capacidad' => 4, 'precio_desde' => 'Consultar', 'descripcion' => '<p>Descripción placeholder de la Cabaña Titicaca: ambientes, vista y distribución. Pendiente de contenido definitivo.</p>'],
            ['slug' => 'cabana-parinacota', 'nombre' => 'Cabaña Parinacota', 'capacidad' => 2, 'precio_desde' => 'Consultar', 'descripcion' => '<p>Descripción placeholder de la Cabaña Parinacota: ambientes, vista y distribución. Pendiente de contenido definitivo.</p>'],
            ['slug' => 'cabana-lauca', 'nombre' => 'Cabaña Lauca', 'capacidad' => 6, 'precio_desde' => 'Consultar', 'descripcion' => '<p>Descripción placeholder de la Cabaña Lauca: ambientes, vista y distribución. Pendiente de contenido definitivo.</p>'],
        ];

        $equipamientoCabana = Equipamiento::ambito('cabana')->pluck('id');
        $equipamientoHabitacion = Equipamiento::ambito('habitacion')->pluck('id');
        $placeholders = ['placeholder/cabana-1.svg', 'placeholder/cabana-2.svg', 'placeholder/cabana-3.svg'];

        foreach ($cabanas as $orden => $data) {
            $cabana = Cabana::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'nombre' => $data['nombre'],
                    'capacidad' => $data['capacidad'],
                    'descripcion' => ['es' => $data['descripcion']],
                    'precio_desde' => $data['precio_desde'],
                    'orden' => $orden,
                    'publicado' => true,
                ]
            );

            $cabana->equipamientos()->sync($equipamientoCabana->merge($equipamientoHabitacion));

            if ($cabana->imagenes()->count() === 0) {
                foreach ($placeholders as $i => $path) {
                    $cabana->imagenes()->create([
                        'path' => $path,
                        'alt' => 'Imagen placeholder: '.$data['nombre'],
                        'orden' => $i,
                    ]);
                }
            }
        }
    }

    private function lugaresEntorno(): void
    {
        $lugares = [
            ['slug' => 'parque-nacional-lauca', 'nombre' => 'Parque Nacional Lauca', 'icono' => 'mountain', 'ubicacion_texto' => 'A ~40 km de Putre', 'descripcion' => '<p>Descripción placeholder del Parque Nacional Lauca.</p>'],
            ['slug' => 'salar-de-surire', 'nombre' => 'Salar de Surire', 'icono' => 'water', 'ubicacion_texto' => 'A ~130 km de Putre', 'descripcion' => '<p>Descripción placeholder del Salar de Surire.</p>'],
            ['slug' => 'termas-de-jurasi', 'nombre' => 'Termas de Jurasi', 'icono' => 'hot-spring', 'ubicacion_texto' => 'A ~15 km de Putre', 'descripcion' => '<p>Descripción placeholder de las Termas de Jurasi.</p>'],
            ['slug' => 'pueblo-de-socoroma', 'nombre' => 'Pueblo de Socoroma', 'icono' => 'village', 'ubicacion_texto' => 'A ~12 km de Putre', 'descripcion' => '<p>Descripción placeholder del pueblo de Socoroma.</p>'],
        ];

        foreach ($lugares as $orden => $data) {
            LugarEntorno::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'nombre' => $data['nombre'],
                    'icono' => $data['icono'],
                    'descripcion' => ['es' => $data['descripcion']],
                    'ubicacion_texto' => $data['ubicacion_texto'],
                    'orden' => $orden,
                    'publicado' => true,
                ]
            );
        }
    }

    private function especies(): void
    {
        $especies = [
            ['nombre_comun' => 'Vicuña', 'nombre_cientifico' => 'Vicugna vicugna', 'tipo' => 'mamifero', 'imagen' => 'placeholder/especie-mamifero.svg'],
            ['nombre_comun' => 'Vizcacha', 'nombre_cientifico' => 'Lagidium viscacia', 'tipo' => 'mamifero', 'imagen' => 'placeholder/especie-mamifero.svg'],
            ['nombre_comun' => 'Flamenco andino', 'nombre_cientifico' => 'Phoenicoparrus andinus', 'tipo' => 'ave', 'imagen' => 'placeholder/especie-ave.svg'],
            ['nombre_comun' => 'Ñandú', 'nombre_cientifico' => 'Rhea pennata', 'tipo' => 'ave', 'imagen' => 'placeholder/especie-ave.svg'],
            ['nombre_comun' => 'Queñoa', 'nombre_cientifico' => 'Polylepis tarapacana', 'tipo' => 'otro', 'imagen' => 'placeholder/especie-otro.svg'],
        ];

        foreach ($especies as $orden => $data) {
            Especie::updateOrCreate(
                ['nombre_cientifico' => $data['nombre_cientifico']],
                [
                    'nombre_comun' => $data['nombre_comun'],
                    'tipo' => $data['tipo'],
                    'descripcion' => ['es' => '<p>Descripción placeholder de '.$data['nombre_comun'].'.</p>'],
                    'donde_observar' => ['es' => '<p>Dónde observar (placeholder): entorno de Putre y el altiplano.</p>'],
                    'imagen' => $data['imagen'],
                    'orden' => $orden,
                    'publicado' => true,
                ]
            );
        }
    }

    private function serviciosLocales(): void
    {
        $servicios = [
            ['nombre' => 'BancoEstado', 'icono' => 'bank'],
            ['nombre' => 'CESFAM Putre', 'icono' => 'health'],
            ['nombre' => 'Carabineros', 'icono' => 'shield'],
            ['nombre' => 'Almacenes y minimarkets', 'icono' => 'store'],
            ['nombre' => 'Guías turísticos locales', 'icono' => 'guide'],
        ];

        foreach ($servicios as $orden => $data) {
            ServicioLocal::firstOrCreate(
                ['nombre' => $data['nombre']],
                ['icono' => $data['icono'], 'orden' => $orden]
            );
        }
    }

    private function paginaNosotros(): void
    {
        $pagina = PaginaNosotros::singleton();
        $pagina->update([
            'historia' => ['es' => '<p>Texto placeholder sobre la historia de Wiñay Pacha Putre y sus anfitriones. Pendiente de contenido definitivo del cliente.</p>'],
            'mensaje' => ['es' => '<p>Texto placeholder sobre el mensaje y propósito del proyecto: difundir la cultura y territorio aymara. Pendiente de contenido definitivo.</p>'],
        ]);
    }
}
