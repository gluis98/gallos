<?php

namespace App\Services;

use App\Models\Gallo;
use App\Models\Gallina;
use App\Models\GallosHijo;

class PedigreeService
{
    /**
     * Coeficiente de consanguinidad simplificado (aproximación por solapamiento de ancestros en 3 generaciones).
     */
    public function calcularConsanguinidad(Gallo $gallo): float
    {
        $ancestors = $this->collectAncestorIds($gallo, 5);
        $counts = array_count_values($ancestors);
        $coef = 0.0;
        foreach ($counts as $id => $n) {
            if ($n > 1) {
                $coef += ($n - 1) * 0.015;
            }
        }

        return round(min($coef, 1.0), 4);
    }

    public function arbolPorGallo(Gallo $gallo, int $depth = 3): array
    {
        return $this->nodoGallo($gallo, $depth);
    }

    public function arbolPorGallina(Gallina $gallina, int $depth = 3): array
    {
        return $this->nodoGallina($gallina, $depth);
    }

    private function nodoGallo(Gallo $gallo, int $depth): array
    {
        $row = $this->filaGenealogica($gallo);
        $node = [
            'tipo' => 'gallo',
            'id' => $gallo->id,
            'label' => $gallo->placa,
            'nombre' => $gallo->nombre,
            'estatus' => $gallo->estatus,
            'rol' => 'sujeto',
        ];
        $node['hijos'] = $this->descendientesDeGallo($gallo);

        if ($depth <= 0) {
            return $node;
        }
        $node['padre'] = $row && $row->padre_id
            ? $this->nodoGalloAncestro(Gallo::query()->find($row->padre_id), $depth - 1)
            : null;
        $node['madre'] = $row && $row->madre_id
            ? $this->nodoGallinaAncestro(Gallina::query()->find($row->madre_id), $depth - 1)
            : null;

        return $node;
    }

    private function nodoGallina(Gallina $gallina, int $depth): array
    {
        $row = $this->filaGenealogica($gallina);
        $node = [
            'tipo' => 'gallina',
            'id' => $gallina->id,
            'label' => $gallina->placa,
            'nombre' => $gallina->nombre,
            'estatus' => $gallina->estatus,
            'rol' => 'sujeto',
        ];
        $node['hijos'] = $this->descendientesDeGallina($gallina);

        if ($depth <= 0) {
            return $node;
        }
        $node['padre'] = $row && $row->padre_id
            ? $this->nodoGalloAncestro(Gallo::query()->find($row->padre_id), $depth - 1)
            : null;
        $node['madre'] = $row && $row->madre_id
            ? $this->nodoGallinaAncestro(Gallina::query()->find($row->madre_id), $depth - 1)
            : null;

        return $node;
    }

    private function nodoGalloAncestro(?Gallo $gallo, int $depth): ?array
    {
        if (! $gallo) {
            return null;
        }

        $node = [
            'tipo' => 'gallo',
            'id' => $gallo->id,
            'label' => $gallo->placa,
            'nombre' => $gallo->nombre,
            'estatus' => $gallo->estatus,
            'rol' => 'padre',
        ];
        if ($depth <= 0) {
            return $node;
        }
        $row = $this->filaGenealogica($gallo);
        $node['padre'] = $row && $row->padre_id
            ? $this->nodoGalloAncestro(Gallo::query()->find($row->padre_id), $depth - 1)
            : null;
        $node['madre'] = $row && $row->madre_id
            ? $this->nodoGallinaAncestro(Gallina::query()->find($row->madre_id), $depth - 1)
            : null;

        return $node;
    }

    private function nodoGallinaAncestro(?Gallina $gallina, int $depth): ?array
    {
        if (! $gallina) {
            return null;
        }

        $node = [
            'tipo' => 'gallina',
            'id' => $gallina->id,
            'label' => $gallina->placa,
            'nombre' => $gallina->nombre,
            'estatus' => $gallina->estatus,
            'rol' => 'madre',
        ];
        if ($depth <= 0) {
            return $node;
        }
        $row = $this->filaGenealogica($gallina);
        $node['padre'] = $row && $row->padre_id
            ? $this->nodoGalloAncestro(Gallo::query()->find($row->padre_id), $depth - 1)
            : null;
        $node['madre'] = $row && $row->madre_id
            ? $this->nodoGallinaAncestro(Gallina::query()->find($row->madre_id), $depth - 1)
            : null;

        return $node;
    }

    /** @return list<array<string, mixed>> */
    private function descendientesDeGallo(Gallo $gallo): array
    {
        return GallosHijo::query()
            ->where('padre_id', $gallo->id)
            ->with('hijoable')
            ->get()
            ->map(fn (GallosHijo $row) => $this->nodoHijo($row))
            ->filter()
            ->values()
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function descendientesDeGallina(Gallina $gallina): array
    {
        return GallosHijo::query()
            ->where('madre_id', $gallina->id)
            ->with('hijoable')
            ->get()
            ->map(fn (GallosHijo $row) => $this->nodoHijo($row))
            ->filter()
            ->values()
            ->all();
    }

    private function nodoHijo(GallosHijo $row): ?array
    {
        $hijo = $row->hijoable;
        if (! $hijo) {
            return null;
        }

        return [
            'tipo' => $hijo instanceof Gallo ? 'gallo' : 'gallina',
            'id' => $hijo->id,
            'label' => $hijo->placa,
            'nombre' => $hijo->nombre,
            'estatus' => $hijo->estatus,
            'rol' => 'hijo',
        ];
    }

    private function filaGenealogica(Gallo|Gallina $ave): ?GallosHijo
    {
        if ($ave instanceof Gallo) {
            return GallosHijo::query()
                ->where('hijoable_type', Gallo::class)
                ->where('hijoable_id', $ave->id)
                ->first();
        }

        return GallosHijo::query()
            ->where('hijoable_type', Gallina::class)
            ->where('hijoable_id', $ave->id)
            ->first();
    }

    private function collectAncestorIds(Gallo $gallo, int $depth): array
    {
        if ($depth <= 0) {
            return [];
        }
        $row = $this->filaGenealogica($gallo);
        if (! $row) {
            return [];
        }
        $ids = array_filter([$row->padre_id, $row->madre_id]);
        if ($row->padre_id) {
            $p = Gallo::query()->find($row->padre_id);
            if ($p) {
                $ids = array_merge($ids, $this->collectAncestorIds($p, $depth - 1));
            }
        }
        if ($row->madre_id) {
            $m = Gallina::query()->find($row->madre_id);
            if ($m) {
                $ids = array_merge($ids, $this->collectAncestorIdsGallina($m, $depth - 1));
            }
        }

        return $ids;
    }

    private function collectAncestorIdsGallina(Gallina $g, int $depth): array
    {
        if ($depth <= 0) {
            return [];
        }
        $row = $this->filaGenealogica($g);
        if (! $row) {
            return [];
        }
        $ids = array_filter([$row->padre_id, $row->madre_id]);
        if ($row->padre_id) {
            $p = Gallo::query()->find($row->padre_id);
            if ($p) {
                $ids = array_merge($ids, $this->collectAncestorIds($p, $depth - 1));
            }
        }
        if ($row->madre_id) {
            $m = Gallina::query()->find($row->madre_id);
            if ($m) {
                $ids = array_merge($ids, $this->collectAncestorIdsGallina($m, $depth - 1));
            }
        }

        return $ids;
    }
}
