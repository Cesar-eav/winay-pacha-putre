<x-winay-layout>
    <x-slot:titulo>Reserva</x-slot:titulo>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-winay-tierra">Solicitar reserva</h1>
        <p class="mt-2 text-stone-600">
            Completa el formulario para solicitar disponibilidad. No es una reserva confirmada:
            el anfitrión revisará tu solicitud manualmente.
        </p>

        <div class="mt-8">
            @livewire('formulario-reserva', ['cabana' => $cabanaSlug])
        </div>
    </section>
</x-winay-layout>
