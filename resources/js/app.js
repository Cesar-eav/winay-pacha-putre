// window.livewireScriptConfig is set server-side by @livewireScriptConfig in
// layouts/winay.blade.php (must run before this module evaluates, since a
// static import here would be hoisted above any inline config we set here).
// Its presence stops Livewire's ESM build from self-starting on
// DOMContentLoaded, since we start it manually below.
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Alpine = Alpine;

Livewire.start();
