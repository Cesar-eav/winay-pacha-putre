<x-winay-layout>
    <x-slot:titulo>Cómo funciona el sitio</x-slot:titulo>
    <x-slot:noindex>true</x-slot:noindex>

    @php
        $estados = [
            'admin' => ['Desde el admin', 'bg-winay-andino/10 text-winay-andino'],
            'mixta' => ['Mixta', 'bg-winay-arena text-winay-tierra'],
            'dev' => ['Requiere desarrollador', 'bg-winay-terracota/10 text-winay-terracota'],
        ];

        $paginas = [
            [
                'nombre' => 'Inicio',
                'ruta' => 'inicio',
                'estado' => 'mixta',
                'admin' => [
                    'El carrusel principal (Admin → Carrusel del inicio): fotos, frase sobre cada foto, encuadre, orden y cuáles se muestran.',
                    'Las fotos de "Quienes ya vivieron Wiñaypacha" (Admin → Fotos de huéspedes): agregar, quitar, reordenar y su texto alternativo.',
                    'Las cabañas y habitaciones que aparecen (Admin → Cabañas / Habitaciones, solo las publicadas y en el orden definido ahí).',
                    'El bloque "Cultura Aymara": muestra el primer tema publicado de la categoría Cultura (Admin → Temas).',
                ],
                'dev' => [
                    'El tiempo entre fotos del carrusel (5 segundos) y su alto en pantalla.',
                    'El bloque "¡Bienvenidos a Wiñaypacha!" (texto y foto).',
                    'Los botones de acceso a Putre, Cultura, Qué Visitar, etc.',
                ],
            ],
            [
                'nombre' => 'Cultura Aymara',
                'ruta' => 'cultura',
                'estado' => 'dev',
                'admin' => [],
                'dev' => [
                    'Toda la página: principios (Suma Qamaña, Ayni, Chachawarmi), la chakana, los colores de la wiphala y el calendario de fiestas del año.',
                ],
            ],
            [
                'nombre' => 'Putre',
                'ruta' => 'putre',
                'estado' => 'admin',
                'admin' => [
                    'Cada artículo de la página es un tema de la categoría "Putre (blog)" (Admin → Temas): título, texto, fotos, orden y si está publicado.',
                ],
                'dev' => [
                    'El encabezado de la página.',
                ],
            ],
            [
                'nombre' => 'Cabañas y Habitaciones',
                'ruta' => 'cabanas.index',
                'estado' => 'admin',
                'admin' => [
                    'Crear, editar, ocultar o reordenar cada cabaña y habitación (Admin → Cabañas / Habitaciones).',
                    'Nombre, capacidad, baños, descripción, precio "desde" del listado y galería de fotos (hasta 12), eligiendo cuál es la portada.',
                    'Equipamiento: tocar lo que tiene cada alojamiento entre más de 100 opciones con dibujo, ordenadas por grupo (cocina, dormitorio, baño, calefacción, exterior…) y con un buscador. En la página se muestra agrupado igual.',
                    'Si algo no está en la lista, se escribe en el buscador y se agrega ahí mismo: el sitio le elige un dibujo según el nombre (o se elige otro, o ninguno) y lo traduce solo al inglés y francés. Lo agregado sirve para todos los alojamientos y se puede quitar de la lista.',
                    'Precios por noche según cantidad de personas, uno para todas las cabañas y otro para todas las habitaciones.',
                ],
                'dev' => [
                    'Sumar opciones a la lista oficial de equipamiento o dibujos nuevos a la galería, y corregir la traducción de un equipamiento agregado desde el panel.',
                    'Los textos de encabezado de los listados.',
                ],
            ],
            [
                'nombre' => 'Qué Visitar',
                'ruta' => 'entorno',
                'estado' => 'admin',
                'admin' => [
                    'Cada lugar (Admin → Qué Visitar — Lugares): nombre, ícono, ubicación, descripción, fotos, orden y si está publicado. Cada lugar tiene su propia página de detalle.',
                ],
                'dev' => [
                    'Mostrar la flora y fauna: se puede cargar en Admin → Qué Visitar — Flora y Fauna, pero hoy ninguna página pública la muestra.',
                ],
            ],
            [
                'nombre' => 'Nosotros',
                'ruta' => 'nosotros',
                'estado' => 'dev',
                'admin' => [],
                'dev' => [
                    'El texto de la familia y la foto.',
                ],
            ],
            [
                'nombre' => 'Contacto',
                'ruta' => 'contacto',
                'estado' => 'mixta',
                'admin' => [
                    'Los mensajes que envían los visitantes llegan a Admin → Contacto y además como aviso a '.config('mail.contacto').'.',
                    'El bloque "¿Para quién es Wiñaypacha?": temas de la categoría "Público objetivo" (Admin → Temas).',
                ],
                'dev' => [
                    'Dirección, teléfono y WhatsApp: se guardan en la configuración del sitio, pero el admin no tiene una pantalla para cambiarlos.',
                    'El correo ('.config('mail.contacto').'): es uno solo para todo el sitio y para recibir los avisos; se cambia en la configuración del proyecto.',
                    'La lista "Servicios en Putre".',
                ],
            ],
            [
                'nombre' => 'Reservas',
                'ruta' => 'reserva',
                'estado' => 'admin',
                'admin' => [
                    'Las solicitudes llegan a Admin → Reservas, donde se les cambia el estado: Nueva, Contactada, Confirmada o Cerrada.',
                    'La lista de alojamientos del formulario se arma sola con las cabañas y habitaciones publicadas.',
                ],
                'dev' => [
                    'Los campos del formulario.',
                ],
            ],
        ];

        $comunes = [
            'Menú, logo y botón "Reservar" del encabezado.',
            'Pie de página: descripción, enlace a Instagram y enlaces. Los datos de contacto del pie (el correo es el mismo de la página Contacto) y el número del botón flotante de WhatsApp salen de la misma configuración sin pantalla en el admin.',
            'Textos fijos de las páginas en inglés y francés (títulos, botones, avisos): se traducen a mano en archivos del proyecto.',
            'Colores, tipografías, diseño y orden de las secciones.',
            'Agregar páginas nuevas o cambiar el menú.',
        ];

        $secciones = [
            ['Dashboard', 'Resumen de mensajes y solicitudes pendientes y cantidad de contenido por sección.'],
            ['Contacto', 'Mensajes del formulario de contacto. Se marcan como atendidos o se eliminan. El número junto al nombre indica los pendientes.'],
            ['Reservas', 'Solicitudes de reserva con nombre, correo, WhatsApp, fechas y alojamiento. Se avanza su estado a medida que se coordinan.'],
            ['Carrusel del inicio', 'Fotos grandes que rotan al abrir el sitio, cada una con una frase opcional encima. Incluye una vista previa de cómo se verá en computador y en celular, y las medidas recomendadas.'],
            ['Temas','Textos con fotos, en tres categorías: Cultura (destacado del inicio), Putre (blog) (página Putre) y Público objetivo (página Contacto).'],
            ['Cabañas / Habitaciones', 'Fichas de alojamiento, fotos, equipamiento y precios por persona.'],
            ['Qué Visitar — Lugares', 'Lugares del entorno de Putre con su página de detalle.'],
            ['Qué Visitar — Flora y Fauna', 'Aves, mamíferos y otras especies con foto. Todavía no se muestran en el sitio.'],
        ];
    @endphp

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold">Guía del sitio</p>
        <h1 class="mt-2 text-3xl sm:text-4xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Cómo funciona el sitio</h1>
        <p class="mt-4 text-stone-600 max-w-2xl">
            El sitio tiene dos partes: las páginas que ven los visitantes y un panel de administración en
            <span class="font-medium text-stone-800">/admin</span>, al que se entra con usuario y contraseña.
            Parte del contenido se cambia directamente desde el panel; el resto está escrito en el código y necesita a un desarrollador web.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-winay-arena p-5">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $estados['admin'][1] }}">{{ $estados['admin'][0] }}</span>
                <p class="mt-3 text-sm text-stone-600">Se edita desde el panel. Los cambios se ven en el sitio al guardar.</p>
            </div>
            <div class="rounded-2xl border border-winay-arena p-5">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $estados['mixta'][1] }}">{{ $estados['mixta'][0] }}</span>
                <p class="mt-3 text-sm text-stone-600">Una parte sale del panel y otra está fija en la página.</p>
            </div>
            <div class="rounded-2xl border border-winay-arena p-5">
                <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $estados['dev'][1] }}">{{ $estados['dev'][0] }}</span>
                <p class="mt-3 text-sm text-stone-600">Está en el código. Cualquier cambio hay que pedirlo al desarrollador.</p>
            </div>
        </div>
    </section>

    <section class="bg-winay-arena/40 border-y border-winay-arena">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-2xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Página por página</h2>

            <div class="mt-8 space-y-4">
                @foreach ($paginas as $pagina)
                    <article class="rounded-2xl bg-white border border-winay-arena p-5 sm:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">
                                <a href="{{ lroute($pagina['ruta']) }}" class="hover:text-winay-terracota">{{ $pagina['nombre'] }}</a>
                            </h3>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $estados[$pagina['estado']][1] }}">{{ $estados[$pagina['estado']][0] }}</span>
                        </div>

                        <div class="mt-4 grid gap-6 sm:grid-cols-2">
                            <div>
                                <p class="text-xs uppercase tracking-wide font-semibold text-winay-andino">Se cambia desde el admin</p>
                                @if ($pagina['admin'])
                                    <ul class="mt-2 space-y-2 text-sm text-stone-600 list-disc pl-4 marker:text-winay-andino">
                                        @foreach ($pagina['admin'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-2 text-sm text-stone-400">Nada en esta página.</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide font-semibold text-winay-terracota">Requiere desarrollador</p>
                                <ul class="mt-2 space-y-2 text-sm text-stone-600 list-disc pl-4 marker:text-winay-terracota">
                                    @foreach ($pagina['dev'] as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </article>
                @endforeach

                <article class="rounded-2xl bg-white border border-winay-arena p-5 sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Común a todo el sitio</h3>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $estados['dev'][1] }}">{{ $estados['dev'][0] }}</span>
                    </div>
                    <ul class="mt-4 space-y-2 text-sm text-stone-600 list-disc pl-4 marker:text-winay-terracota">
                        @foreach ($comunes as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-display font-medium tracking-[-0.01em] text-winay-tierra">El panel de administración</h2>
        <dl class="mt-6 divide-y divide-winay-arena border-y border-winay-arena">
            @foreach ($secciones as [$nombre, $descripcion])
                <div class="py-4 grid gap-1 sm:grid-cols-3 sm:gap-6">
                    <dt class="font-medium text-stone-800">{{ $nombre }}</dt>
                    <dd class="text-sm text-stone-600 sm:col-span-2">{{ $descripcion }}</dd>
                </div>
            @endforeach
        </dl>

        <div class="mt-12 grid gap-8 sm:grid-cols-2">
            <div>
                <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Idiomas</h3>
                <div class="mt-3 space-y-3 text-sm text-stone-600">
                    <p>El sitio está en español, inglés y francés. En el panel basta con escribir en español: al guardar, el texto se traduce solo al inglés y al francés.</p>
                    <p>Las pestañas EN y FR de cada campo sirven para revisar o corregir la traducción. Si se corrige a mano, esa versión se respeta; si después cambia el texto en español, conviene revisarla de nuevo.</p>
                    <p>El botón «Traducir con DeepL» muestra la traducción antes de guardar. Si ya hay texto en inglés o francés, pide confirmar porque lo reemplaza. Si después de usarlo se cambia el español, al guardar se vuelve a traducir. Un punto rojo en una pestaña EN o FR indica que ese idioma tiene un error que impide guardar.</p>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Fotos</h3>
                <div class="mt-3 space-y-3 text-sm text-stone-600">
                    <p>Se pueden subir fotos directo desde el celular, incluidas las de iPhone. El sitio las achica y optimiza solo para que carguen rápido.</p>
                    <p>El orden de la galería se cambia arrastrando las fotos. En cabañas y habitaciones, un toque sobre una foto la deja como portada.</p>
                    <p>Carrusel del inicio: fotos horizontales de al menos 2000 px de ancho (ideal 2400 × 1350, formato 16:9), con lo importante al centro. No se aceptan fotos de menos de 1200 px de ancho.</p>
                    <p>Fotos de huéspedes: verticales (formato 3:4, ideal 1200 × 1600 px), con las personas al centro. Mínimo 600 px de ancho. Solo de huéspedes que hayan aceptado aparecer en el sitio.</p>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Publicar y ordenar</h3>
                <div class="mt-3 space-y-3 text-sm text-stone-600">
                    <p>Todo contenido tiene una casilla "Publicado": si se desmarca, deja de verse en el sitio pero no se borra.</p>
                    <p>El número de "Orden" define en qué posición aparece: los números más bajos van primero.</p>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-display font-medium tracking-[-0.01em] text-winay-tierra">Reservas</h3>
                <div class="mt-3 space-y-3 text-sm text-stone-600">
                    <p>El sitio no confirma reservas ni cobra pagos. El formulario solo envía una solicitud, que hay que responder por correo o WhatsApp.</p>
                    <p>Cada solicitud de reserva y cada mensaje de contacto llega también como aviso a {{ config('mail.contacto') }}. Al responder ese correo, la respuesta va directo al visitante. Todo queda igual guardado en el panel, aunque el aviso no llegue.</p>
                </div>
            </div>
        </div>
    </section>
</x-winay-layout>
