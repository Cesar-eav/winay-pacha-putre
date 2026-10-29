<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Huespedes;
use App\Models\Galeria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HuespedesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create(['is_admin' => true]));
    }

    public function test_sube_fotos_con_texto_alternativo_y_las_muestra_en_el_inicio(): void
    {
        $this->get(route('inicio'))->assertOk()->assertDontSee('Quienes ya vivieron Wiñaypacha');

        $componente = Livewire::test(Huespedes::class)
            ->set('nuevasFotos', [UploadedFile::fake()->image('a.jpg', 1200, 1600)]);

        $clave = $componente->get('ordenGaleria')[0];

        $componente->set("altsGaleria.$clave", 'Huéspedes en el mirador')
            ->call('guardar')
            ->assertHasNoErrors();

        $imagen = Galeria::de(Galeria::HUESPEDES)->imagenes()->sole();
        Storage::disk('public')->assertExists($imagen->path);

        $this->get(route('inicio'))->assertOk()
            ->assertSee('Quienes ya vivieron Wiñaypacha')
            ->assertSee('Huéspedes en el mirador');
    }

    public function test_rechaza_fotos_angostas(): void
    {
        $componente = Livewire::test(Huespedes::class)
            ->set('nuevasFotos', [UploadedFile::fake()->image('chica.jpg', 400, 600)]);

        $this->assertSame([], $componente->get('ordenGaleria'));
        $this->assertSame('chica.jpg', $componente->get('erroresFotos')[0]['nombre']);
    }
}
