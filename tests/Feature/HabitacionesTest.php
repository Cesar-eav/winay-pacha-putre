<?php

namespace Tests\Feature;

use App\Livewire\Admin\Cabanas;
use App\Livewire\Admin\Habitaciones;
use App\Models\Cabana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HabitacionesTest extends TestCase
{
    use RefreshDatabase;

    private function crear(string $tipo, string $slug, int $numero): Cabana
    {
        return Cabana::create([
            'tipo' => $tipo, 'numero' => $numero, 'slug' => $slug, 'nombre' => ucfirst($slug),
            'descripcion' => ['es' => 'Descripción'], 'publicado' => true,
        ]);
    }

    public function test_admin_crea_habitacion_con_numero_independiente_de_las_cabanas(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->crear(Cabana::TIPO_CABANA, 'cabana-uno', 1);

        Livewire::test(Habitaciones::class)
            ->call('nuevo')
            ->set('numero', 1)
            ->set('nombre', 'Habitación Matrimonial')
            ->set('descripcion.es', 'Descripción')
            ->call('guardar')
            ->assertHasNoErrors();

        $habitacion = Cabana::where('slug', 'habitacion-matrimonial')->firstOrFail();
        $this->assertSame(Cabana::TIPO_HABITACION, $habitacion->tipo);

        Livewire::test(Cabanas::class)
            ->assertDontSee('Habitación Matrimonial')
            ->call('nuevo')
            ->set('numero', 1)
            ->set('nombre', 'Otra cabaña')
            ->set('descripcion.es', 'Descripción')
            ->call('guardar')
            ->assertHasErrors('numero');

        $this->get(route('admin.habitaciones'))->assertOk()->assertSee('Habitación Matrimonial');
    }

    public function test_paginas_publicas_separan_cabanas_y_habitaciones(): void
    {
        $this->crear(Cabana::TIPO_CABANA, 'cabana-uno', 1);
        $this->crear(Cabana::TIPO_HABITACION, 'habitacion-uno', 1);

        $this->get(route('habitaciones.index'))->assertOk()
            ->assertSee('Habitacion-uno')->assertDontSee('Cabana-uno')
            ->assertSee(route('habitaciones.show', 'habitacion-uno'));
        $this->get(route('cabanas.index'))->assertOk()
            ->assertSee('Cabana-uno')->assertDontSee('Habitacion-uno');

        $this->get(route('habitaciones.show', 'habitacion-uno'))->assertOk()
            ->assertSee('Habitación N° 1')->assertSee('Reservar esta habitación');
        $this->get(route('en.habitaciones.show', 'habitacion-uno'))->assertOk()->assertSee('Book this room');

        $this->get(route('habitaciones.show', 'cabana-uno'))->assertNotFound();
        $this->get(route('cabanas.show', 'habitacion-uno'))->assertNotFound();

        $this->get(route('reserva', ['cabana' => 'habitacion-uno']))->assertOk()
            ->assertSee('<optgroup label="Habitaciones">', false);
    }
}
