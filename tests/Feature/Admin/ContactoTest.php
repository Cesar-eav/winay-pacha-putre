<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Contacto;
use App\Models\LeadContacto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContactoTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtra_marca_atendido_y_elimina(): void
    {
        $this->actingAs(User::factory()->create());

        $pendiente = LeadContacto::create(['nombre' => 'Ana Pendiente', 'correo' => 'ana@example.com', 'mensaje' => 'Hola']);
        LeadContacto::create(['nombre' => 'Luis Atendido', 'correo' => 'luis@example.com', 'mensaje' => 'Hola', 'atendido' => true]);

        $componente = Livewire::test(Contacto::class)
            ->assertSee('Ana Pendiente')
            ->assertDontSee('Luis Atendido');

        $componente->call('alternarAtendido', $pendiente->id);
        $this->assertTrue($pendiente->fresh()->atendido);

        $componente->set('filtro', 'atendidos')
            ->assertSee('Ana Pendiente')
            ->assertSee('Luis Atendido')
            ->call('eliminar', $pendiente->id);

        $this->assertNull($pendiente->fresh());
    }

    public function test_requiere_login(): void
    {
        $this->get('/admin/contacto')->assertRedirect('/login');
    }
}
