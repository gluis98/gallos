<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGalloRequest;
use App\Http\Requests\UpdateGalloRequest;
use App\Models\Gallo;
use App\Models\Gallina;
use App\Models\GallosHijo;
use App\Models\GallosImagene;
use App\Services\PedigreeService;
use Illuminate\Http\Request;

class GalloController extends Controller
{
    public function __construct(
        protected PedigreeService $pedigreeService
    ) {}

    public function index()
    {
        $g = Gallo::query()
            ->with('gallos_imagenes', 'gallos_hijos.padre', 'ventas')
            ->get();

        return response()->json([
            'data' => $g,
        ], 200);
    }

    public function store(StoreGalloRequest $request)
    {
        $this->authorize('create', Gallo::class);
        $data = $request->validated();
        unset($data['imagen'], $data['padre_id'], $data['madre_id']);
        $g = Gallo::query()->create($data);

        if ($request->filled('padre_id') || $request->filled('madre_id')) {
            GallosHijo::query()->create(array_filter([
                'padre_id' => $request->input('padre_id'),
                'madre_id' => $request->input('madre_id'),
                'hijoable_type' => Gallo::class,
                'hijoable_id' => $g->id,
                'tipo' => 'gallo',
            ], fn ($v) => $v !== null && $v !== ''));
        }

        if ($request->hasFile('imagen')) {
            foreach ($request->file('imagen') as $file) {
                $file->move(public_path('files/gallos/'.$g->id.'/'), $file->getClientOriginalName());
                GallosImagene::query()->create([
                    'gallo_id' => $g->id,
                    'imagen' => $file->getClientOriginalName(),
                ]);
            }
        }

        return response()->json([
            'msj' => 'Registro registrado exitosamente',
        ], 200);
    }

    public function show(string $id)
    {
        $g = Gallo::query()->with([
            'gallos_imagenes',
            'gallos_hijos.padre.gallos_imagenes',
            'gallos_hijos.madre.gallinas_imagenes',
            'ventas',
            'hijos.hijoable',
        ])->find($id);

        return response()->json([
            'data' => $g,
        ], 200);
    }

    public function update(UpdateGalloRequest $request, $id)
    {
        $g = Gallo::query()->findOrFail($id);
        $data = $request->validated();
        unset($data['imagen'], $data['padre_id'], $data['madre_id']);
        $g->fill($data)->save();

        GallosHijo::query()
            ->where('hijoable_type', Gallo::class)
            ->where('hijoable_id', $id)
            ->delete();

        if ($request->filled('padre_id') || $request->filled('madre_id')) {
            GallosHijo::query()->create(array_filter([
                'padre_id' => $request->input('padre_id'),
                'madre_id' => $request->input('madre_id'),
                'hijoable_type' => Gallo::class,
                'hijoable_id' => $g->id,
                'tipo' => 'gallo',
            ], fn ($v) => $v !== null && $v !== ''));
        }

        if ($request->hasFile('imagen')) {
            GallosImagene::query()->where('gallo_id', $id)->delete();
            foreach ($request->file('imagen') as $file) {
                $file->move(public_path('files/gallos/'.$g->id.'/'), $file->getClientOriginalName());
                GallosImagene::query()->create([
                    'gallo_id' => $g->id,
                    'imagen' => $file->getClientOriginalName(),
                ]);
            }
        }

        return response()->json([
            'msj' => 'Registro actualizado exitosamente',
        ], 200);
    }

    public function destroy($id)
    {
        $g = Gallo::query()->findOrFail($id);
        GallosHijo::query()
            ->where('hijoable_type', Gallo::class)
            ->where('hijoable_id', $id)
            ->delete();
        $g->delete();

        return response()->json([
            'msj' => 'Registro eliminado exitosamente',
        ], 200);
    }

    public function search(Request $request)
    {
        $dato = $request->input('dato', '');
        $g = Gallo::query()
            ->with('gallos_imagenes', 'gallos_hijos.padre', 'ventas')
            ->where(function ($q) use ($dato) {
                $q->where('placa', 'like', '%'.$dato.'%')
                    ->orWhere('marca_nacimiento', 'like', '%'.$dato.'%')
                    ->orWhere('nombre', 'like', '%'.$dato.'%')
                    ->orWhere('color', 'like', '%'.$dato.'%')
                    ->orWhere('color_alternativo', 'like', '%'.$dato.'%');
            })
            ->get();

        return response()->json([
            'data' => $g,
        ], 200);
    }

    public function pedigree(string $id)
    {
        $gallo = Gallo::query()->findOrFail($id);

        return response()->json([
            'data' => $this->pedigreeService->arbolPorGallo($gallo, 3),
            'consanguinidad' => $this->pedigreeService->calcularConsanguinidad($gallo),
        ]);
    }
}
