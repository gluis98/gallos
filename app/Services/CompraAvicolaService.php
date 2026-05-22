<?php

namespace App\Services;

use App\Models\CompraItem;
use App\Models\Gallina;
use App\Models\Gallo;
use Illuminate\Support\Str;

class CompraAvicolaService
{
    /**
     * Registra gallo o gallina en su módulo a partir de un ítem de compra.
     */
    public function registrarDesdeCompra(CompraItem $item, int $compraId, int $lineIndex): ?array
    {
        if (! in_array($item->tipo_ave, ['gallo', 'gallina'], true)) {
            return null;
        }

        $costo = (float) $item->costo;
        $notaCompra = sprintf('Registrado desde compra #%d · Costo USD %s', $compraId, number_format($costo, 2, '.', ''));
        $observaciones = trim(implode("\n", array_filter([
            $item->observaciones,
            $notaCompra,
        ])));

        if ($item->tipo_ave === 'gallo') {
            $gallo = Gallo::query()->create([
                'placa' => $this->resolverPlaca($item->placa, 'G', $compraId, $lineIndex),
                'nombre' => $item->nombre,
                'marca_nacimiento' => $item->marca_nacimiento,
                'color' => $item->color,
                'observaciones' => $observaciones ?: null,
                'estatus' => 'Activo',
            ]);

            $item->update(['gallo_id' => $gallo->id]);

            return ['tipo' => 'gallo', 'id' => $gallo->id, 'placa' => $gallo->placa];
        }

        $gallina = Gallina::query()->create([
            'placa' => $this->resolverPlaca($item->placa, 'C', $compraId, $lineIndex),
            'nombre' => $item->nombre,
            'marca_nacimiento' => $item->marca_nacimiento,
            'color' => $item->color,
            'observaciones' => $observaciones ?: null,
            'estatus' => 'Activa',
        ]);

        $item->update(['gallina_id' => $gallina->id]);

        return ['tipo' => 'gallina', 'id' => $gallina->id, 'placa' => $gallina->placa];
    }

    private function resolverPlaca(?string $placa, string $prefijo, int $compraId, int $lineIndex): string
    {
        $placa = trim((string) $placa);
        if ($placa !== '') {
            return Str::limit($placa, 255, '');
        }

        return sprintf('%s-%d-%d', $prefijo, $compraId, $lineIndex + 1);
    }
}
