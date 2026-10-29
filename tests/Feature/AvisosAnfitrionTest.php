<?php

namespace Tests\Feature;

use App\Livewire\FormularioContacto;
use App\Livewire\FormularioReserva;
use App\Mail\NuevaSolicitudReserva;
use App\Mail\NuevoMensajeContacto;
use App\Models\Cabana;
use App\Models\LeadContacto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class AvisosAnfitrionTest extends TestCase
{
    use RefreshDatabase;

    public function test_mensaje_de_contacto_avisa_al_anfitrion(): void
    {
        Mail::fake();

        Livewire::test(FormularioContacto::class)
            ->set('nombre', 'Ana')
            ->set('correo', 'ana@example.com')
            ->set('mensaje', 'Hola, ¿tienen disponibilidad?')
            ->call('guardar')
            ->assertHasNoErrors()
            ->assertSet('enviado', true);

        Mail::assertSent(NuevoMensajeContacto::class, function (NuevoMensajeContacto $correo) {
            return $correo->hasTo(config('mail.contacto'))
                && $correo->hasReplyTo('ana@example.com');
        });
    }

    public function test_solicitud_de_reserva_avisa_al_anfitrion(): void
    {
        Mail::fake();
        $cabana = Cabana::create([
            'tipo' => Cabana::TIPO_CABANA, 'numero' => 1, 'slug' => 'cabana-uno', 'nombre' => 'Cabaña Uno',
            'descripcion' => ['es' => 'Descripción'], 'publicado' => true,
        ]);

        Livewire::test(FormularioReserva::class)
            ->set('nombre', 'Luis')
            ->set('apellido', 'Mamani')
            ->set('correo', 'luis@example.com')
            ->set('whatsapp', '+56 9 1111 2222')
            ->set('fechaLlegada', now()->addDays(5)->toDateString())
            ->set('fechaSalida', now()->addDays(8)->toDateString())
            ->set('numPersonas', 2)
            ->set('cabanaId', $cabana->id)
            ->call('guardar')
            ->assertHasNoErrors()
            ->assertSet('enviado', true);

        Mail::assertSent(NuevaSolicitudReserva::class, function (NuevaSolicitudReserva $correo) {
            $html = $correo->render();

            return $correo->hasTo(config('mail.contacto'))
                && $correo->hasReplyTo('luis@example.com')
                && str_contains($html, 'Cabaña Uno');
        });
    }

    public function test_si_el_correo_falla_el_mensaje_igual_se_guarda(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('Mailgun caído'));

        Livewire::test(FormularioContacto::class)
            ->set('nombre', 'Ana')
            ->set('correo', 'ana@example.com')
            ->set('mensaje', 'Hola')
            ->call('guardar')
            ->assertSet('enviado', true);

        $this->assertSame(1, LeadContacto::count());
    }
}
