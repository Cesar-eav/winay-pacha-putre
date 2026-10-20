<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Temas;
use App\Models\Tema;
use App\Models\User;
use App\Services\DeepLTranslator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class TraduccionAutomaticaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['winay.deepl_api_key' => 'clave-test:fx']);
        $this->actingAs(User::factory()->create());
    }

    private function fakeDeepL(): void
    {
        Http::fake(fn (Request $request) => Http::response([
            'translations' => [['text' => "[{$request['target_lang']}] {$request['text']}"]],
        ]));
    }

    private function crearTema(): Tema
    {
        return Tema::create([
            'slug' => 'pachamama',
            'categoria' => 'cultura',
            'titulo' => ['es' => 'Pachamama', 'en' => 'Mother Earth', 'fr' => 'Terre Mère'],
            'cuerpo' => ['es' => '<p>Texto</p>', 'en' => '<p>Text</p>', 'fr' => '<p>Texte</p>'],
            'orden' => 0,
            'publicado' => true,
        ]);
    }

    public function test_al_crear_traduce_los_idiomas_vacios(): void
    {
        $this->fakeDeepL();

        Livewire::test(Temas::class)
            ->call('nuevo')
            ->set('titulo.es', 'Año nuevo aymara')
            ->set('cuerpo.es', '<p>El <strong>Machaq Mara</strong></p>')
            ->call('guardar')
            ->assertHasNoErrors();

        $tema = Tema::firstWhere('slug', 'ano-nuevo-aymara');
        $this->assertSame('[EN] Año nuevo aymara', $tema->getTranslation('titulo', 'en'));
        $this->assertSame('[FR] <p>El <strong>Machaq Mara</strong></p>', $tema->getTranslation('cuerpo', 'fr'));

        Http::assertSent(fn (Request $r) => $r['source_lang'] === 'ES' && ($r->data()['tag_handling'] ?? null) === 'html' && str_contains($r['text'], '<strong>'));
        Http::assertSent(fn (Request $r) => $r['text'] === 'Año nuevo aymara' && ! isset($r['tag_handling']));
    }

    public function test_desescapa_apostrofes_del_html_traducido(): void
    {
        Http::fake(['*' => Http::response(['translations' => [['text' => '<p>L&#x27;altiplano</p>']]])]);

        $this->assertSame("<p>L'altiplano</p>", app(DeepLTranslator::class)->translate('<p>El altiplano</p>', 'fr'));
    }

    public function test_si_cambia_el_es_retraduce_los_idiomas_no_editados(): void
    {
        $this->fakeDeepL();
        $tema = $this->crearTema();

        Livewire::test(Temas::class)
            ->call('editar', $tema->id)
            ->set('titulo.es', 'La Pachamama')
            ->set('titulo.en', 'The Mother Earth')
            ->call('guardar')
            ->assertHasNoErrors();

        $tema->refresh();
        $this->assertSame('The Mother Earth', $tema->getTranslation('titulo', 'en'));
        $this->assertSame('[FR] La Pachamama', $tema->getTranslation('titulo', 'fr'));
        $this->assertSame('<p>Text</p>', $tema->getTranslation('cuerpo', 'en'));
        Http::assertSentCount(1);
    }

    public function test_sin_cambios_en_es_no_llama_a_deepl(): void
    {
        $this->fakeDeepL();
        $tema = $this->crearTema();

        Livewire::test(Temas::class)
            ->call('editar', $tema->id)
            ->set('orden', 3)
            ->call('guardar')
            ->assertHasNoErrors();

        Http::assertNothingSent();
    }

    public function test_si_deepl_falla_guarda_igual_y_avisa(): void
    {
        Http::fake(fn () => Http::response([], 500));

        Livewire::test(Temas::class)
            ->call('nuevo')
            ->set('titulo.es', 'Wiphala')
            ->set('cuerpo.es', '<p>Bandera</p>')
            ->call('guardar')
            ->assertHasNoErrors()
            ->assertSee('DeepL no respondió');

        $tema = Tema::firstWhere('slug', 'wiphala');
        $this->assertNotNull($tema);
        $this->assertSame('', (string) $tema->getTranslation('titulo', 'en', false));
    }
}
