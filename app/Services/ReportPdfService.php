<?php

namespace App\Services;

use App\Models\Gallina;
use App\Models\Gallo;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ReportPdfService
{
    public function streamGalloFicha(Gallo $gallo): Response
    {
        return $this->pdfResponse(
            'reports.pdf.ficha-gallo',
            $this->galloFichaData($gallo),
            'ficha-gallo-'.$this->safeFilename($gallo->placa).'.pdf'
        );
    }

    public function streamGallinaFicha(Gallina $gallina): Response
    {
        return $this->pdfResponse(
            'reports.pdf.ficha-gallina',
            $this->gallinaFichaData($gallina),
            'ficha-gallina-'.$this->safeFilename($gallina->placa).'.pdf'
        );
    }

    public function streamGallosCatalog(Collection $gallos): Response
    {
        return $this->pdfResponse(
            'reports.pdf.catalogo-gallos',
            [
                'criadero' => $this->criaderoName(),
                'items' => $gallos->map(fn (Gallo $g) => $this->galloFichaData($g))->values(),
                'generated_at' => now()->format('d/m/Y H:i'),
                'total' => $gallos->count(),
            ],
            'catalogo-gallos-'.now()->format('Y-m-d').'.pdf'
        );
    }

    public function streamGallinasCatalog(Collection $gallinas): Response
    {
        return $this->pdfResponse(
            'reports.pdf.catalogo-gallinas',
            [
                'criadero' => $this->criaderoName(),
                'items' => $gallinas->map(fn (Gallina $g) => $this->gallinaFichaData($g))->values(),
                'generated_at' => now()->format('d/m/Y H:i'),
                'total' => $gallinas->count(),
            ],
            'catalogo-gallinas-'.now()->format('Y-m-d').'.pdf'
        );
    }

    public function galloFichaData(Gallo $gallo): array
    {
        $gallo->loadMissing(['gallos_imagenes', 'gallos_hijos.padre.gallos_imagenes', 'gallos_hijos.madre.gallinas_imagenes']);

        $padre = $gallo->gallos_hijos?->padre;
        $madre = $gallo->gallos_hijos?->madre;

        return [
            'criadero' => $this->criaderoName(),
            'generated_at' => now()->format('d/m/Y H:i'),
            'placa' => $gallo->placa,
            'nombre' => $gallo->nombre,
            'estatus' => $gallo->estatus,
            'foto' => $this->mainGalloImage($gallo),
            'fields' => $this->galloFields($gallo),
            'observaciones' => $gallo->observaciones,
            'padre' => $padre ? $this->parentCardGallo($padre) : null,
            'madre' => $madre ? $this->parentCardGallina($madre) : null,
        ];
    }

    public function gallinaFichaData(Gallina $gallina): array
    {
        $gallina->loadMissing(['gallinas_imagenes', 'gallos_hijos.padre.gallos_imagenes', 'gallos_hijos.madre.gallinas_imagenes']);

        $padre = $gallina->gallos_hijos?->padre;
        $madre = $gallina->gallos_hijos?->madre;

        return [
            'criadero' => $this->criaderoName(),
            'generated_at' => now()->format('d/m/Y H:i'),
            'placa' => $gallina->placa,
            'nombre' => $gallina->nombre,
            'estatus' => $gallina->estatus,
            'foto' => $this->mainGallinaImage($gallina),
            'fields' => $this->gallinaFields($gallina),
            'observaciones' => $gallina->observaciones,
            'padre' => $padre ? $this->parentCardGallo($padre) : null,
            'madre' => $madre ? $this->parentCardGallina($madre) : null,
        ];
    }

    private function pdfResponse(string $view, array $data, string $filename): Response
    {
        $pdf = Pdf::loadView($view, $data)
            ->setPaper('a4', 'portrait')
            ->setOption([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        if (request()->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    private function criaderoName(): string
    {
        if (tenancy()->initialized) {
            return (string) (tenant('name') ?? 'Criadero');
        }

        $user = auth()->user();
        if ($user) {
            $tenantId = tenancy()->initialized
                ? tenant('id')
                : app(\App\Services\UserGalponService::class)->resolveActiveTenantId($user);
            if ($tenantId) {
                return (string) (Tenant::query()->find($tenantId)?->name ?? 'Criadero');
            }
        }

        return 'Criadero';
    }

    private function galloFields(Gallo $g): array
    {
        return array_filter([
            ['label' => 'Nombre', 'value' => $g->nombre],
            ['label' => 'Marca de nacimiento', 'value' => $g->marca_nacimiento ?? $g->marca],
            ['label' => 'Marca / Federación', 'value' => $g->marca_federacion],
            ['label' => 'Anillo', 'value' => $g->anillo],
            ['label' => 'Fecha de nacimiento', 'value' => $g->fecha_nacimiento],
            ['label' => 'Color', 'value' => $g->color],
            ['label' => 'Color alternativo', 'value' => $g->color_alternativo],
            ['label' => 'Cresta', 'value' => $g->cresta],
            ['label' => 'Luna', 'value' => $g->luna],
            ['label' => 'N° de peleas', 'value' => $g->peleas],
        ], fn ($row) => filled($row['value'] ?? null));
    }

    private function gallinaFields(Gallina $g): array
    {
        return array_filter([
            ['label' => 'Nombre', 'value' => $g->nombre],
            ['label' => 'Marca de nacimiento', 'value' => $g->marca_nacimiento ?? $g->marca],
            ['label' => 'Marca / Federación', 'value' => $g->marca_federacion],
            ['label' => 'Anillo', 'value' => $g->anillo],
            ['label' => 'Fecha de nacimiento', 'value' => $g->fecha_nacimiento],
            ['label' => 'Color', 'value' => $g->color],
            ['label' => 'Color alternativo', 'value' => $g->color_alternativo],
            ['label' => 'Cresta', 'value' => $g->cresta],
            ['label' => 'Luna', 'value' => $g->luna],
        ], fn ($row) => filled($row['value'] ?? null));
    }

    private function parentCardGallo(Gallo $g): array
    {
        return [
            'tipo' => 'Gallo',
            'placa' => $g->placa,
            'nombre' => $g->nombre,
            'estatus' => $g->estatus,
            'marca' => $g->marca_nacimiento ?? $g->marca,
            'color' => $g->color,
            'peleas' => $g->peleas,
            'observaciones' => $g->observaciones,
            'foto' => $this->mainGalloImage($g),
        ];
    }

    private function parentCardGallina(Gallina $g): array
    {
        return [
            'tipo' => 'Gallina',
            'placa' => $g->placa,
            'nombre' => $g->nombre,
            'estatus' => $g->estatus,
            'marca' => $g->marca_nacimiento ?? $g->marca,
            'color' => $g->color,
            'observaciones' => $g->observaciones,
            'foto' => $this->mainGallinaImage($g),
        ];
    }

    private function mainGalloImage(Gallo $g): ?string
    {
        $img = $g->gallos_imagenes->first();
        if ($img?->imagen) {
            return $this->encodeImage(public_path("files/gallos/{$g->id}/{$img->imagen}"));
        }

        return $this->encodeImage(public_path('img/avatar.png'));
    }

    private function mainGallinaImage(Gallina $g): ?string
    {
        $img = $g->gallinas_imagenes->first();
        if ($img?->imagen) {
            return $this->encodeImage(public_path("files/gallinas/{$g->id}/{$img->imagen}"));
        }

        return $this->encodeImage(public_path('img/avatar-2.png'));
    }

    private function encodeImage(?string $path): ?string
    {
        if (! $path || ! is_file($path)) {
            return null;
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($path));
    }

    private function logoBase64(): ?string
    {
        return $this->encodeImage(public_path('img/logo.png'));
    }

    public function logo(): ?string
    {
        return $this->logoBase64();
    }

    private function safeFilename(string $placa): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $placa);

        return trim($safe, '-') ?: 'ave';
    }
}
