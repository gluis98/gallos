<?php

namespace App\Support;

use App\Models\Gallo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class MarketplacePresenter
{
    public static function isGallo(?Model $ave, string $aveType): bool
    {
        return $aveType === Gallo::class;
    }

    public static function aveImages(?Model $ave, bool $isGallo): Collection
    {
        if (! $ave) {
            return collect();
        }

        $images = $isGallo
            ? ($ave->gallos_imagenes ?? collect())
            : ($ave->gallinas_imagenes ?? collect());

        $folder = $isGallo ? 'gallos' : 'gallinas';

        return $images
            ->map(function ($img) use ($ave, $folder) {
                $file = $img->imagen ?? $img->path ?? $img->ruta ?? null;

                return $file ? asset("files/{$folder}/{$ave->id}/{$file}") : null;
            })
            ->filter()
            ->values();
    }

    public static function aveLabel(bool $isGallo): string
    {
        return $isGallo ? 'Gallo' : 'Gallina';
    }
}
