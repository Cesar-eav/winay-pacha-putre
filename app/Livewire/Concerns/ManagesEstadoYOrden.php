<?php

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait ManagesEstadoYOrden
{
    abstract protected function consultaListado(): Builder;

    public function alternarPublicado(int $id): void
    {
        $modelo = $this->consultaListado()->findOrFail($id);
        $modelo->update(['publicado' => ! $modelo->publicado]);
    }

    public function moverOrden(int $id, string $direccion): void
    {
        $items = $this->consultaListado()->orderBy('orden')->orderBy('id')->get();

        $indice = $items->search(fn ($item) => $item->id === $id);
        $destino = $direccion === 'arriba' ? $indice - 1 : $indice + 1;

        if ($indice === false || ! $items->has($destino)) {
            return;
        }

        $reordenado = $items->all();
        [$reordenado[$indice], $reordenado[$destino]] = [$reordenado[$destino], $reordenado[$indice]];

        foreach ($reordenado as $posicion => $item) {
            if ((int) $item->orden !== $posicion) {
                $item->update(['orden' => $posicion]);
            }
        }
    }
}
