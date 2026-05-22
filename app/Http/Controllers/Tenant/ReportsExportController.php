<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Gallo;
use App\Services\ReportPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;

class ReportsExportController extends Controller
{
    public function __construct(
        protected ReportPdfService $reportPdf
    ) {}

    public function galloPdf(Request $request, int $id)
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

    public function inventarioExcel(Request $request)
    {
        $collection = Gallo::query()->with('ventas')->get()->map(fn ($g) => [
            'id' => $g->id,
            'placa' => $g->placa,
            'estatus' => $g->estatus,
            'ventas' => $g->ventas->count(),
        ]);

        $export = new class($collection) implements FromCollection
        {
            public function __construct(private Collection $rows) {}

            public function collection()
            {
                return $this->rows;
            }
        };

        return Excel::download($export, 'inventario.xlsx');
    }
}
