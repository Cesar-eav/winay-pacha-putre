<x-winay-layout>
    <x-slot:titulo>{{ __('Cultura Aymara') }}</x-slot:titulo>

    @php
        $principios = [
            ['nombre' => 'Suma Qamaña', 'traduccion' => __('"Vivir bien"'), 'texto' => __('Una vida en armonía con la tierra y la comunidad, sin acumular por encima del equilibrio colectivo.')],
            ['nombre' => 'Ayni', 'traduccion' => __('Reciprocidad'), 'texto' => __('La ayuda entregada siempre vuelve de alguna forma: la base de la colaboración comunitaria andina.')],
            ['nombre' => 'Chachawarmi', 'traduccion' => __('Complementariedad'), 'texto' => __('El equilibrio entre el hombre y la mujer en cada tarea, decisión y celebración.')],
        ];

        $chakana = [
            ['nombre' => 'Alaxpacha', 'lugar' => __('el cielo'), 'texto' => __('Representado por el cóndor, mensajero de los Apus (espíritus de las montañas).')],
            ['nombre' => 'Akapacha', 'lugar' => __('la tierra'), 'texto' => __('El mundo donde vivimos junto a la Pachamama, representado por el puma.')],
            ['nombre' => 'Manghapacha', 'lugar' => __('el mundo subterráneo'), 'texto' => __('Representado por la serpiente, protectora de los ríos y canales.')],
        ];

        $wiphala = [
            ['color' => '#FFFFFF', 'nombre' => __('Blanco'), 'valor' => __('Tiempo, pureza y comunicación.')],
            ['color' => '#FFD200', 'nombre' => __('Amarillo'), 'valor' => __('Energía, fuerza y solidaridad.')],
            ['color' => '#FF8200', 'nombre' => __('Naranjo'), 'valor' => __('Creatividad, cultura y salud.')],
            ['color' => '#E10600', 'nombre' => __('Rojo'), 'valor' => __('La tierra y su esencia.')],
            ['color' => '#6B2C91', 'nombre' => __('Morado'), 'valor' => __('Trascendencia y cosmovisión andina.')],
            ['color' => '#0056A8', 'nombre' => __('Azul'), 'valor' => __('El espacio cósmico.')],
            ['color' => '#2E8540', 'nombre' => __('Verde'), 'valor' => __('Economía y producción.')],
        ];

        $meses = [
            [
                'mes' => 'Enero',
                'fechas' => [
                    ['fecha' => '30-31 de enero', 'texto' => 'Anata de los pueblos.'],
                ],
            ],
            [
                'mes' => 'Febrero',
                'fechas' => [
                    ['fecha' => '5 de febrero', 'texto' => 'Aniversario de Wiñaypacha.'],
                    ['fecha' => '7-14 de febrero', 'texto' => 'Carnaval de Putre.'],
                ],
            ],
            [
                'mes' => 'Mayo',
                'fechas' => [
                    ['fecha' => '3 de mayo', 'texto' => 'Cruz de Mayo, mes de la Chakana — dura una semana.'],
                    ['fecha' => '9 de mayo', 'texto' => 'Ascenso a los cerros sagrados a dejar las cruces.'],
                    ['fecha' => 'Mayo', 'texto' => 'Feria mayo.'],
                ],
            ],
            [
                'mes' => 'Junio',
                'fechas' => [
                    ['fecha' => '21 de junio', 'texto' => "Mara T'aqa: se recibe el nuevo ciclo aymara con la Wilancha."],
                ],
            ],
            [
                'mes' => 'Julio',
                'fechas' => [
                    ['fecha' => '16 de julio', 'texto' => 'Virgen del Carmen.'],
                ],
            ],
            [
                'mes' => 'Agosto',
                'fechas' => [
                    ['fecha' => '1 de agosto', 'texto' => 'Wilancha.'],
                    ['fecha' => '14-16 de agosto', 'texto' => 'Virgen de Asunta.'],
                ],
            ],
            [
                'mes' => 'Noviembre',
                'fechas' => [
                    ['fecha' => '1 de noviembre', 'texto' => 'Wiñay Pacha, día de las almas.'],
                    ['fecha' => '3-4 de noviembre', 'texto' => 'Pachallampi.'],
                ],
            ],
        ];
    @endphp

    {{-- Hero full-bleed --}}
    <section class="relative w-full h-[70vh] min-h-96 max-h-160 overflow-hidden">
        <img
            src="{{ asset('images/culturaaymara/web/cosmovision2.jpg') }}"
            alt="{{ __('La Chakana, cruz andina de tres escalones, con los colores de la Wiphala sobre un paisaje de volcanes altiplánicos al atardecer') }}"
            class="absolute inset-0 w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 lg:pb-16 w-full">
                <p class="text-sm uppercase tracking-widest text-winay-arena/90 font-semibold mb-3 drop-shadow">
                    {{ __('Putre, territorio aymara') }}
                </p>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white drop-shadow-lg max-w-3xl">
                    {{ __('Cultura Aymara') }}
                </h1>
                <p class="mt-4 text-lg lg:text-xl text-white/90 max-w-2xl leading-relaxed drop-shadow">
                    {{ __('Cosmovisión, textiles y fiestas que siguen dando vida a un pueblo milenario en el corazón del altiplano.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Nav interno (desktop) --}}
    <nav
        x-data="{
            active: 'cosmovision',
            init() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) this.active = entry.target.id
                    })
                }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 })
                ;['cosmovision', 'textiles', 'calendario'].forEach(id => {
                    const el = document.getElementById(id)
                    if (el) observer.observe(el)
                })
            },
        }"
        class="hidden lg:block sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-winay-arena"
    >
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8 h-14">
                <a href="#cosmovision" :class="active === 'cosmovision' ? 'text-winay-terracota' : 'text-stone-500 hover:text-winay-terracota'" class="text-sm font-semibold transition">{{ __('Cosmovisión') }}</a>
                <a href="#textiles" :class="active === 'textiles' ? 'text-winay-terracota' : 'text-stone-500 hover:text-winay-terracota'" class="text-sm font-semibold transition">{{ __('Textiles') }}</a>
                <a href="#calendario" :class="active === 'calendario' ? 'text-winay-terracota' : 'text-stone-500 hover:text-winay-terracota'" class="text-sm font-semibold transition">{{ __('Calendario de fiestas') }}</a>
            </div>
        </div>
    </nav>

    {{-- Cosmovisión aymara --}}
    <section id="cosmovision" class="scroll-mt-32 bg-white border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid gap-10 lg:grid-cols-2 items-center">
                <div>
                    <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('Cosmovisión') }}</p>
                    <h2 class="text-3xl lg:text-5xl font-bold tracking-tight text-winay-tierra">{{ __('Vivir en equilibrio con la Pachamama') }}</h2>
                    <p class="mt-4 text-lg lg:text-xl text-stone-600 leading-relaxed">
                        {{ __('La cosmovisión aymara no es solo un conjunto de creencias: es una manera de habitar el territorio que sigue guiando la vida cotidiana en Putre.') }}
                    </p>
                    <p class="mt-4 text-stone-600 leading-relaxed">
                        {{ __('En el centro de todo está la Pachamama (Madre Tierra), con quien se vive en constante reciprocidad. Tres principios sostienen ese equilibrio:') }}
                    </p>
                </div>
                <img
                    src="{{ asset('images/culturaaymara/web/cosmovision1.jpg') }}"
                    alt="{{ __('Pareja aymara con vestimenta tradicional y textil andino, mirando el atardecer en el altiplano') }}"
                    class="w-full aspect-video rounded-2xl object-cover"
                >
            </div>

            {{-- Tarjetas de principios --}}
            <div class="mt-14 grid gap-6 sm:grid-cols-3">
                @foreach ($principios as $p)
                    <div class="p-6 rounded-2xl border border-winay-arena">
                        <h3 class="font-semibold text-lg text-winay-tierra">{{ $p['nombre'] }}</h3>
                        <p class="mt-1 text-sm text-stone-500 italic">{{ $p['traduccion'] }}</p>
                        <p class="mt-3 text-stone-600 leading-relaxed">{{ $p['texto'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Chakana + Wiphala --}}
    <section class="bg-winay-arena/40 border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-2 items-start">
                {{-- Diagrama de la Chakana --}}
                <div>
                    <h3 class="text-2xl font-bold text-winay-tierra">{{ __('La Chakana: la escalera entre tres mundos') }}</h3>
                    <p class="mt-3 text-stone-600 leading-relaxed">{{ __('La cruz andina también ordena el tiempo, las cosechas y las fiestas que aún se celebran en Putre.') }}</p>

                    <div class="mt-8 space-y-3">
                        @foreach ($chakana as $i => $nivel)
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-white border border-winay-arena" style="margin-left: {{ $i * 1.5 }}rem">
                                <span class="flex items-center justify-center size-10 rounded-full bg-winay-terracota/10 text-winay-terracota font-bold shrink-0">{{ $i + 1 }}</span>
                                <div>
                                    <p class="font-semibold text-winay-tierra">{{ $nivel['nombre'] }} <span class="text-stone-400 font-normal">— {{ $nivel['lugar'] }}</span></p>
                                    <p class="text-sm text-stone-600">{{ $nivel['texto'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Legend Wiphala --}}
                <div>
                    <h3 class="text-2xl font-bold text-winay-tierra">{{ __('La Wiphala') }}</h3>
                    <p class="mt-3 text-stone-600 leading-relaxed">{{ __('La bandera de siete colores resume esta cosmovisión: cada color representa un valor propio del pueblo aymara.') }}</p>
                    <dl class="mt-6 space-y-2">
                        @foreach ($wiphala as $c)
                            <div class="flex items-center gap-3">
                                <span class="size-4 rounded-sm border border-stone-300 shrink-0" style="background-color: {{ $c['color'] }}" aria-hidden="true"></span>
                                <dt class="sr-only">{{ $c['nombre'] }}</dt>
                                <dd class="text-sm text-stone-600"><span class="font-semibold text-stone-800">{{ $c['nombre'] }}:</span> {{ $c['valor'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>

            <p class="mt-12 text-sm text-stone-400 text-center">{{ __('Contenidos inspirados en "Cosmovisión Aymara: un acercamiento para profesionales de salud", Javiera Quispe Villalobos, Universidad de Antofagasta (2021).') }}</p>
        </div>
    </section>

    {{-- Textiles y artesanía --}}
    <section id="textiles" class="scroll-mt-32 bg-white border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('Artesanía') }}</p>
            <h2 class="text-3xl lg:text-5xl font-bold tracking-tight text-winay-tierra max-w-3xl">{{ __('Textiles y artesanía') }}</h2>

            <div class="mt-12 grid gap-10 lg:grid-cols-5 items-start">
                <div class="lg:col-span-3">
                    <x-galeria-lightbox :imagenes="collect([
                        ['url' => asset('images/culturaaymara/web/artesanias1.jpg'), 'alt' => __('Puesto de artesanía en Putre con tejidos, chompas de lana y textiles andinos colgados')],
                        ['url' => asset('images/culturaaymara/web/artesanias2.jpg'), 'alt' => __('Detalle de tejidos, bolsos y artesanía en lana con motivos andinos')],
                        ['url' => asset('images/culturaaymara/web/artesanias3.jpg'), 'alt' => __('Chompas, mantas y textiles artesanales a la venta en un puesto de Putre')],
                    ])" titulo="Textiles y artesanía" />
                </div>
                <div class="lg:col-span-2 max-w-prose text-lg text-stone-600 leading-relaxed space-y-4">
                    <p>{{ __('El tejido es una de las expresiones más vivas de la cultura aymara. En el altiplano de Putre, las familias siguen hilando y tejiendo con lana de alpaca y llama, animales que además de dar sustento son parte esencial de la cosmovisión andina.') }}</p>
                    <p>{{ __('El awayo —la manta multicolor que se usa para cargar, abrigar y decorar— es quizás la pieza textil más reconocible: acompaña tanto la vida diaria como las ceremonias y fiestas patronales.') }}</p>
                    <blockquote class="border-l-4 border-winay-terracota pl-4 text-xl italic text-winay-tierra">
                        {{ __('"Sawuña": el oficio de tejer se transmite de generación en generación, principalmente entre mujeres.') }}
                    </blockquote>
                    <p>{{ __('Hoy, artesanas y artesanos de Putre mantienen vivo el telar tradicional y ofrecen sus tejidos, cerámicas y piezas en madera a quienes visitan el pueblo: una oportunidad genuina de llevarse contigo un pedazo de esta cultura.') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Calendario de fiestas --}}
    <section id="calendario" class="scroll-mt-32 bg-winay-arena/40 border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <p class="text-sm uppercase tracking-wide text-winay-terracota font-semibold mb-2">{{ __('El año aymara') }}</p>
            <h2 class="text-3xl lg:text-5xl font-bold tracking-tight text-winay-tierra max-w-3xl">{{ __('Calendario de fiestas') }}</h2>
            <p class="mt-4 text-lg text-stone-600 leading-relaxed max-w-2xl">
                {{ __('El calendario aymara se mide en ciclos agrícolas, rituales y fiestas patronales que marcan el ritmo del año en Putre. Muchas celebraciones mezclan la tradición andina con el catolicismo llegado hace siglos. Si tu visita coincide con alguna de estas fechas, no te la pierdas.') }}
            </p>

            {{-- Tarjetas destacadas --}}
            <div class="mt-12 grid gap-6 sm:grid-cols-2">
                <div class="rounded-2xl overflow-hidden bg-white border border-winay-arena">
                    <img src="{{ asset('images/culturaaymara/web/fiestas1.jpg') }}" alt="{{ __('Banda y bailarines en la plaza de Putre durante la Anata de los pueblos') }}" class="w-full aspect-4/3 object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-winay-terracota">{{ __('30-31 de enero') }}</p>
                        <h3 class="mt-1 font-semibold text-winay-tierra">{{ __('Anata de los pueblos') }}</h3>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-white border border-winay-arena">
                    <img src="{{ asset('images/culturaaymara/web/fiestas3.jpg') }}" alt="{{ __('Procesión ascendiendo a los cerros sagrados a dejar las cruces') }}" class="w-full aspect-4/3 object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-winay-terracota">{{ __('9 de mayo') }}</p>
                        <h3 class="mt-1 font-semibold text-winay-tierra">{{ __('Ascenso a los cerros sagrados a dejar las cruces') }}</h3>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-white border border-winay-arena">
                    <img src="{{ asset('images/culturaaymara/web/fiestas2.jpg') }}" alt="{{ __('Autoridades y reina de fiesta en atuendo tradicional durante una celebración patronal de Putre') }}" class="w-full aspect-4/3 object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-stone-400">{{ __('Fiesta patronal') }}</p>
                        <h3 class="mt-1 font-semibold text-winay-tierra">{{ __('Autoridades y reina de fiesta') }}</h3>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden bg-white border border-winay-arena">
                    <img src="{{ asset('images/culturaaymara/web/fiestas4.jpg') }}" alt="{{ __('Mesa ritual con flores y hojas de coca durante una ceremonia comunitaria en Putre') }}" class="w-full aspect-4/3 object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-stone-400">{{ __('Ceremonia ritual') }}</p>
                        <h3 class="mt-1 font-semibold text-winay-tierra">{{ __('Mesa ritual y ofrenda a la Pachamama') }}</h3>
                    </div>
                </div>
            </div>

            {{-- Timeline compacto del resto de fechas --}}
            <div class="mt-14 max-w-3xl">
                <h3 class="text-lg font-semibold text-winay-tierra mb-4">{{ __('Resto del año') }}</h3>
                <div class="divide-y divide-winay-arena border-y border-winay-arena">
                    @foreach ($meses as $item)
                        <div x-data="{ open: false }" class="py-1">
                            <button
                                type="button"
                                @click="open = !open"
                                class="w-full flex items-center justify-between gap-4 py-3 text-left"
                            >
                                <span class="text-lg font-semibold text-winay-tierra">{{ __($item['mes']) }}</span>
                                <svg
                                    class="h-5 w-5 shrink-0 text-winay-terracota transition-transform"
                                    :class="open ? 'rotate-180' : ''"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak x-transition class="pb-4 space-y-2">
                                @foreach ($item['fechas'] as $fecha)
                                    <p class="text-stone-600">
                                        <span class="font-semibold text-stone-800">{{ $fecha['fecha'] }}:</span>
                                        {{ __($fecha['texto']) }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CTA de cierre --}}
    <section class="bg-white border-t border-winay-arena">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-2xl lg:text-3xl font-bold text-winay-tierra">{{ __('Vive esta cultura desde tus propios ojos') }}</h2>
            <p class="mt-3 text-stone-600 max-w-xl mx-auto">{{ __('Nuestras cabañas son la base ideal para descubrir el altiplano de Putre y su gente.') }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ lroute('cabanas.index') }}" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold text-white bg-winay-terracota hover:bg-winay-tierra transition">
                    {{ __('Ver cabañas') }}
                </a>
                <a href="{{ lroute('reserva') }}" class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold text-winay-tierra border border-winay-tierra hover:bg-winay-arena transition">
                    {{ __('Solicitar reserva') }}
                </a>
            </div>
        </div>
    </section>
</x-winay-layout>
