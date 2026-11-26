// window.livewireScriptConfig is set server-side by @livewireScriptConfig in
// layouts/winay.blade.php (must run before this module evaluates, since a
// static import here would be hoisted above any inline config we set here).
// Its presence stops Livewire's ESM build from self-starting on
// DOMContentLoaded, since we start it manually below.
import '@fontsource-variable/newsreader/opsz.css';
import '@fontsource-variable/ibm-plex-sans';
import '@fontsource-variable/fraunces/soft.css';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;

Alpine.data('mapaRuta', (textos) => ({
    init() {
        const observador = new IntersectionObserver(([entrada]) => {
            if (! entrada.isIntersecting) return;
            observador.disconnect();
            import('./mapa-ruta.js').then(({ montar }) => montar(this.$el, textos));
        }, { rootMargin: '300px' });
        observador.observe(this.$el);
    },
}));

Livewire.start();
