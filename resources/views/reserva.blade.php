<x-winay-layout>
    <x-slot:titulo>Reserva</x-slot:titulo>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-winay-tierra">Solicitar reserva</h1>
        <p class="mt-2 text-stone-600">
            Cuéntanos tu fecha ideal y te contactaremos personalmente por correo o WhatsApp para confirmar
            disponibilidad — cada solicitud la revisamos nosotros mismos, no un sistema automático.
        </p>

        <div class="mt-8">
            @livewire('formulario-reserva', ['cabana' => $cabanaSlug])
        </div>
    </section>
</x-winay-layout>
