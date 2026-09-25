// Igual que resources/js/app.js (ver comentario allí sobre @livewireScriptConfig
// y el arranque manual de Livewire), pero exclusivo del panel admin: agrega
// Quill y lo registra como componente Alpine ANTES de Livewire.start(), porque
// Alpine.data() debe existir antes de que Alpine escanee el DOM inicial.
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

window.Alpine = Alpine;

Alpine.data('richtextEditor', (content) => ({
    quill: null,
    content,

    init() {
        this.quill = new Quill(this.$refs.editor, {
            theme: 'snow',
            placeholder: 'Escribe aquí…',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'link'],
                    ['clean'],
                ],
            },
        });

        if (this.content) {
            this.quill.clipboard.dangerouslyPasteHTML(this.content);
        }

        this.quill.on('text-change', () => {
            const html = this.quill.root.innerHTML;
            this.content = html === '<p><br></p>' ? '' : html;
        });

        // El botón "Traducir con DeepL" llena en/fr desde el servidor: si el
        // valor entangled cambia por fuera de este editor (no por text-change
        // arriba), hay que reflejarlo en Quill.
        this.$watch('content', (value) => {
            const actual = this.quill.root.innerHTML === '<p><br></p>' ? '' : this.quill.root.innerHTML;

            if (value !== actual) {
                this.quill.setContents([]);

                if (value) {
                    this.quill.clipboard.dangerouslyPasteHTML(value);
                }
            }
        });
    },
}));

Livewire.start();
