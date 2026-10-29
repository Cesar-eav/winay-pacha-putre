<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Carrusel;
use App\Models\ImagenCarrusel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CarruselTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        config(['winay.deepl_api_key' => 'clave-test:fx']);
        Http::fake(fn (Request $request) => Http::response([
            'translations' => [['text' => "[{$request['target_lang']}] {$request['text']}"]],
        ]));
        $this->actingAs(User::factory()->create(['is_admin' => true]));
    }

    public function test_crea_imagen_traduce_la_frase_y_la_muestra_en_el_inicio(): void
    {
        Livewire::test(Carrusel::class)
            ->call('nuevo')
            ->set('nuevaFoto', UploadedFile::fake()->image('cerros.jpg', 2400, 1350))
            ->set('frase.es', 'Al pie de los cerros')
            ->set('encuadre', 'abajo')
            ->call('guardar')
            ->assertHasNoErrors();

        $imagen = ImagenCarrusel::sole();
        $this->assertSame('[EN] Al pie de los cerros', $imagen->getTranslation('frase', 'en'));
        Storage::disk('public')->assertExists([$imagen->imagen, 'carrusel/medium/'.basename($imagen->imagen)]);

        $this->get(route('inicio'))->assertOk()
            ->assertSee('Al pie de los cerros')
            ->assertSee('object-[50%_65%]', false);
    }

    public function test_rechaza_fotos_angostas_y_exige_foto_al_crear(): void
    {
        Livewire::test(Carrusel::class)
            ->call('nuevo')
            ->call('guardar')
            ->assertHasErrors(['nuevaFoto' => 'required'])
            ->set('nuevaFoto', UploadedFile::fake()->image('chica.jpg', 800, 600))
            ->assertHasErrors(['nuevaFoto' => 'dimensions']);

        $this->assertSame(0, ImagenCarrusel::count());
    }

    public function test_reemplazar_y_eliminar_borran_los_archivos_y_el_inicio_se_oculta_sin_fotos(): void
    {
        $componente = Livewire::test(Carrusel::class)
            ->call('nuevo')
            ->set('nuevaFoto', UploadedFile::fake()->image('a.jpg', 2000, 1200))
            ->call('guardar');

        $imagen = ImagenCarrusel::sole();
        $original = $imagen->imagen;

        $componente->call('editar', $imagen->id)
            ->set('nuevaFoto', UploadedFile::fake()->image('b.jpg', 2000, 1200))
            ->call('guardar')
            ->assertHasNoErrors();

        Storage::disk('public')->assertMissing($original);
        $nueva = $imagen->fresh()->imagen;

        $componente->call('eliminar', $imagen->id);

        Storage::disk('public')->assertMissing($nueva);
        $this->get(route('inicio'))->assertOk()->assertDontSee('Ir a la imagen');
    }
}
