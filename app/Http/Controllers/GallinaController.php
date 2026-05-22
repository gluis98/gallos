<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGallinaRequest;
use App\Http\Requests\UpdateGallinaRequest;
use App\Models\Gallina;
use App\Models\GallinasImagene;
use App\Models\Gallo;
use App\Models\GallosHijo;
use App\Services\PedigreeService;
use Illuminate\Http\Request;

class GallinaController extends Controller
{
    public function __construct(
        protected PedigreeService $pedigreeService
    ) {}

    public function index()
    {
        $g = Gallina::query()
            ->with('gallinas_imagenes', 'gallos_hijos.padre', 'gallos_hijos.padre.gallos_imagenes')
            ->get();

        return response()->json([
            'data' => $g,
        ], 200);
    }

    public function store(StoreGallinaRequest $request)
    {
        $this->authorize('create', Gallina::class);
        $data = $request->validated();
        unset($data['imagen'], $data['padre_id'], $data['madre_id']);
        $g = Gallina::query()->create($data);

        if ($request->filled('padre_id') || $request->filled('madre_id')) {
            GallosHijo::query()->create(array_filter([
                'padre_id' => $request->input('padre_id'),
                'madre_id' => $request->input('madre_id'),
                'hijoable_type' => Gallina::class,
                'hijoable_id' => $g->id,
                'tipo' => 'gallina',
            ], fn ($v) => $v !== null && $v !== ''));
        }

        if ($request->hasFile('imagen')) {
            foreach ($request->file('imagen') as $file) {
                $file->move(public_path('files/gallinas/'.$g->id.'/'), $file->getClientOriginalName());
                GallinasImagene::query()->create([
                    'gallina_id' => $g->id,
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
        $g = Gallina::query()->with([
            'gallinas_imagenes',
            'gallos_hijos.padre',
            'gallos_hijos.padre.gallos_imagenes',
            'gallos_hijos.madre',
            'gallos_hijos.madre.gallinas_imagenes',
            'hijos.hijoable',
        ])->find($id);

        return response()->json([
            'data' => $g,
        ], 200);
    }

    public function update(UpdateGallinaRequest $request, $id)
    {
        $g = Gallina::query()->findOrFail($id);
        $this->authorize('update', $g);
        $data = $request->validated();
        unset($data['imagen'], $data['padre_id'], $data['madre_id']);
        $g->fill($data)->save();

        GallosHijo::query()
            ->where('hijoable_type', Gallina::class)
            ->where('hijoable_id', $id)
            ->delete();

        if ($request->filled('padre_id') || $request->filled('madre_id')) {
            GallosHijo::query()->create(array_filter([
                'padre_id' => $request->input('padre_id'),
                'madre_id' => $request->input('madre_id'),
                'hijoable_type' => Gallina::class,
                'hijoable_id' => $g->id,
                'tipo' => 'gallina',
            ], fn ($v) => $v !== null && $v !== ''));
        }

        if ($request->hasFile('imagen')) {
            foreach ($request->file('imagen') as $file) {
                $file->move(public_path('files/gallinas/'.$id.'/'), $file->getClientOriginalName());
                GallinasImagene::query()->create([
                    'gallina_id' => $id,
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
        $g = Gallina::query()->findOrFail($id);
        $this->authorize('delete', $g);

        GallosHijo::query()
            ->where('hijoable_type', Gallina::class)
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
        $g = Gallina::query()
            ->with('gallinas_imagenes', 'gallos_hijos.madre', 'gallos_hijos.padre')
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
        $gallina = Gallina::query()->findOrFail($id);

        return response()->json([
            'data' => $this->pedigreeService->arbolPorGallina($gallina, 3),
        ]);
    }
}
