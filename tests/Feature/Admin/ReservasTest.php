<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Reservas;
use App\Models\SolicitudReserva;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReservasTest extends TestCase
{
    use RefreshDatabase;

    private function solicitud(string $nombre, string $estado = 'nuevo'): SolicitudReserva
    {
        return SolicitudReserva::create([
            'nombre' => $nombre,
            'apellido' => 'Prueba',
            'correo' => 'x@example.com',
            'whatsapp' => '+56 9 1234 5678',
            'fecha_llegada' => '2026-12-01',
            'fecha_salida' => '2026-12-04',
            'num_personas' => 2,
            'estado' => $estado,
        ]);
    }

    public function test_filtra_cambia_estado_y_elimina(): void
    {
        $this->actingAs(User::factory()->create());

        $nueva = $this->solicitud('Ana');
        $this->solicitud('Luis', 'confirmado');

        $componente = Livewire::test(Reservas::class)
            ->assertSee('Ana Prueba')
            ->assertDontSee('Luis Prueba');

        $componente->call('cambiarEstado', $nueva->id, 'contactado');
        $this->assertSame('contactado', $nueva->fresh()->estado);

        $componente->call('cambiarEstado', $nueva->id, 'inventado')->assertHasErrors('estado');
        $this->assertSame('contactado', $nueva->fresh()->estado);

        $componente->set('filtro', 'todas')
            ->assertSee('Ana Prueba')
            ->assertSee('Luis Prueba')
            ->call('eliminar', $nueva->id);

        $this->assertNull($nueva->fresh());
    }

    public function test_requiere_login(): void
    {
        $this->get('/admin/reservas')->assertRedirect('/login');
    }
}
