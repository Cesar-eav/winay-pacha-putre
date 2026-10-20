<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Cabanas;
use App\Models\Cabana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class GaleriaCabanasTest extends TestCase
{
    use RefreshDatabase;

    public function test_ordena_marca_principal_y_reporta_archivos_invalidos(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $componente = Livewire::test(Cabanas::class)
            ->call('nuevo')
            ->set('nombre', 'Cabaña Prueba')
            ->set('descripcion.es', 'Descripción')
            ->upload('nuevasFotos', [UploadedFile::fake()->image('a.jpg')])
            ->upload('nuevasFotos', [UploadedFile::fake()->image('b.jpg')])
            ->upload('nuevasFotos', [UploadedFile::fake()->create('roto.jpg', 10, 'image/jpeg')])
            ->upload('nuevasFotos', [UploadedFile::fake()->image('c.jpg')]);

        $orden = $componente->get('ordenGaleria');
        $this->assertCount(3, $orden);
        $this->assertSame('roto.jpg', $componente->get('erroresFotos')[0]['nombre']);
        $this->assertSame($orden[0], $componente->get('clavePrincipal'));

        $componente->call('ordenarGaleria', $orden[2], 0)
            ->call('marcarPrincipal', $orden[1])
            ->call('guardar')
            ->assertHasNoErrors();

        $cabana = Cabana::with('imagenes')->firstWhere('slug', 'cabana-prueba');
        $this->assertCount(3, $cabana->imagenes);
        $this->assertSame($cabana->imagenes[2]->path, $cabana->imagen_principal);
        $this->assertSame($cabana->imagen_principal, $cabana->galeriaPublica()->first()->path);

        $componente->call('editar', $cabana->id);
        $principal = $componente->get('clavePrincipal');
        $this->assertSame('e-'.$cabana->imagenes[2]->id, $principal);

        $componente->call('eliminarFoto', $principal)
            ->call('guardar')
            ->assertHasNoErrors();

        $cabana->refresh()->load('imagenes');
        $this->assertCount(2, $cabana->imagenes);
        $this->assertSame($cabana->imagenes[0]->path, $cabana->imagen_principal);
    }

    public function test_respeta_el_maximo_de_imagenes(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create());

        $componente = Livewire::test(Cabanas::class)->call('nuevo');

        for ($i = 0; $i < 13; $i++) {
            $componente->upload('nuevasFotos', [UploadedFile::fake()->image("f$i.jpg")]);
        }

        $this->assertCount(12, $componente->get('ordenGaleria'));
        $this->assertSame('f12.jpg', $componente->get('erroresFotos')[0]['nombre']);
    }
}
