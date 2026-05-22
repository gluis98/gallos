<?php

namespace App\Http\Controllers;

use App\Models\Gallo;
use App\Models\Gallina;
use App\Services\ReportPdfService;

class ReporteController extends Controller
{
    public function __construct(
        protected ReportPdfService $reportPdf
    ) {}

    public function all()
    {
        $g = Gallo::query()
            ->with([
                'gallos_imagenes',
                'gallos_hijos.padre.gallos_imagenes',
                'gallos_hijos.madre.gallinas_imagenes',
            ])
            ->orderBy('placa')
            ->get();

        return $this->reportPdf->streamGallosCatalog($g);
    }

    public function show($id)
    {
        $g = Gallo::query()
            ->with([
                'gallos_imagenes',
                'gallos_hijos.padre.gallos_imagenes',
                'gallos_hijos.madre.gallinas_imagenes',
            ])
            ->findOrFail($id);

        return $this->reportPdf->streamGalloFicha($g);
    }

    public function allGallinas()
    {
        $g = Gallina::query()
            ->with([
                'gallinas_imagenes',
                'gallos_hijos.padre.gallos_imagenes',
                'gallos_hijos.madre.gallinas_imagenes',
            ])
            ->orderBy('placa')
            ->get();

        return $this->reportPdf->streamGallinasCatalog($g);
    }

    public function showGallina($id)
    {
        $g = Gallina::query()
            ->with([
                'gallinas_imagenes',
                'gallos_hijos.padre.gallos_imagenes',
                'gallos_hijos.madre.gallinas_imagenes',
            ])
            ->findOrFail($id);

        return $this->reportPdf->streamGallinaFicha($g);
    }
}
