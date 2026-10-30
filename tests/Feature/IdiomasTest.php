<?php

namespace Tests\Feature;

use App\Livewire\FormularioReserva;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IdiomasTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_admin_queda_en_espanol_aunque_se_haya_visitado_otro_idioma(): void
    {
        $this->get('/fr')->assertOk();
        $this->assertSame('fr', app()->getLocale());

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin')
            ->assertOk();

        $this->assertSame('es', app()->getLocale());
    }

    public function test_el_formulario_de_reserva_valida_en_el_idioma_de_la_pagina(): void
    {
        $casos = [
            'es' => 'El campo WhatsApp no debe tener más de 50 caracteres.',
            'en' => 'The WhatsApp field must not be greater than 50 characters.',
            'fr' => 'Le champ WhatsApp ne doit pas dépasser 50 caractères.',
        ];

        foreach ($casos as $locale => $mensaje) {
            app()->setLocale($locale);

            Livewire::test(FormularioReserva::class)
                ->set('whatsapp', str_repeat('9', 60))
                ->set('fechaLlegada', 'no es fecha')
                ->call('guardar')
                ->assertHasErrors(['whatsapp' => $mensaje])
                ->assertDontSee('validation.');
        }
    }

    public function test_el_selector_de_idioma_conserva_el_query_string(): void
    {
        $this->get('/reserva?cabana=vicuna')
            ->assertOk()
            ->assertSee('href="'.url('/en/reserva').'?cabana=vicuna"', false);
    }
}
