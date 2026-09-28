import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

window.Alpine = Alpine;

Alpine.data('richtextEditor', (content) => {
    // Fuera del estado de Alpine a propósito: si Quill cuelga de `this`,
    // Alpine lo envuelve en un Proxy y se rompe la identidad de sus blots.
    let quill = null;

    const getHTML = () => {
        const html = quill.root.innerHTML;
        return html === '<p><br></p>' ? '' : html;
    };

    return {
        content,

        init() {
            quill = new Quill(this.$refs.editor, {
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
                // clipboard.convert() + setContents() reemplaza todo el
                // documento por un camino distinto (y más directo) que
                // dangerouslyPasteHTML, que internamente simula un evento de
                // "paste" (updateContents con retain+concat). En Quill 2.x
                // dangerouslyPasteHTML dejaba el árbol de blots en un estado
                // que rompía con "blot is null" en la siguiente interacción.
                quill.setContents(quill.clipboard.convert({ html: this.content }), 'silent');
            }

            quill.on('text-change', () => {
                this.content = getHTML();
            });

            // Solo aplicamos valores entrantes cuando el editor NO tiene foco
            // (ver comentario original sobre DeepL / wire:model.live.debounce).
            this.$watch('content', (value) => {
                if (quill.hasFocus()) {
                    return;
                }

                if (value !== getHTML()) {
                    if (value) {
                        quill.setContents(quill.clipboard.convert({ html: value }), 'silent');
                    } else {
                        quill.setText('', 'silent');
                    }
                }
            });
        },
    };
});

Livewire.start();