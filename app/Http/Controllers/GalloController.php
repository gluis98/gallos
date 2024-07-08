<?php

namespace App\Http\Controllers;

use App\Models\Gallina;
use App\Models\Gallo;
use App\Models\GallosHijo;
use App\Models\GallosImagene;

use Illuminate\Http\Request;

class GalloController extends Controller
{
    public function index()
    {
        $g = Gallo::with('gallos_imagenes', 'gallos_hijos', 'gallos_hijos.padre', 'ventas')->get();
        return response()->json([
            'data' => $g
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $g = Gallo::create($request->all())->latest('id')->first();

        if($request->padre_id != null){
            $padre = Gallo::where('placa', $request->padre_id)->first();
            $gh = GallosHijo::create([ 
                'padre_id' => $padre->id,
                'hijo_id' => $g->id,
                'tipo' => 'Gallo'
            ])->latest('id')->first();
        }

        if($request->madre_id != null){
            $madre = Gallina::where('placa', $request->madre_id)->first();
            if(!empty($madre)){
                $ghm = GallosHijo::find($gh->id);
                $ghm->madre_id = $madre->id;
                $ghm->save();
            }
        }

        if($request->hasFile('imagen')){
            foreach($request->file('imagen') as $file){
                $name = $file->getClientOriginalName();
                $file->move(public_path('files/gallos/' . $g->id . '/'), $name);
                $gi = GallosImagene::create([
                                'gallo_id' => $g->id, 
                                'imagen' => $file->getClientOriginalName()
                    ]);
            }
        }

        return response()->json([
            'msj' => "Registro registrado exitosamente"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $g = Gallo::with('gallos_imagenes', 
                         'gallos_hijos',
                         'gallos_hijos',
                         'gallos_hijos.gallo',
                         'gallos_hijos.gallo.gallos_imagenes', 
                         'gallos_hijos.padre', 
                         'gallos_hijos.padre.gallos_imagenes', 
                         'gallos_hijos.madre', 
                         'gallos_hijos.madre.gallinas_imagenes', 
                         'gallos_hijos.gallina', 
                         'ventas', 
                         'hijos',
                         'hijos.gallo',
                         'hijos.gallo.gallos_imagenes')->find($id);
        return response()->json([
            'data' => $g
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $g = Gallo::find($id);
        if($request->padre_id != null){
            $gh = GallosHijo::where('hijo_id', $id)->delete();
            $gh = GallosHijo::create([ 
                'padre_id' => $request->padre_id,
                'hijo_id' => $id,
                'tipo' => 'Gallo'
            ])->latest('id')->first();
        }

        if($request->padre_id == null && $request->madre_id != null){
            $madre = Gallina::find($request->madre_id);
            $gh = GallosHijo::where('hijo_id', $id)->delete();
            $gh = GallosHijo::create([ 
                'madre_id' => $request->madre_id,
                'hijo_id' => $id,
                'tipo' => 'Gallo'
            ])->latest('id')->first();
        }

        if($request->madre_id != null){
            $madre = Gallina::find($request->madre_id);
            if(!empty($madre)){
                $ghm = GallosHijo::find($gh->id);
                $ghm->madre_id = $madre->id;
                $ghm->save();
            }
        }

        

        $g->fill($request->all())->save();
        
        if($request->hasFile('imagen')){
            GallosImagene::where('gallo_id', $id)->delete();
            foreach($request->file('imagen') as $file){
                $name = $file->getClientOriginalName();
                $file->move(public_path('files/gallos/' . $g->id . '/'), $name);
                $gi = GallosImagene::create([
                                'gallo_id' => $g->id, 
                                'imagen' => $file->getClientOriginalName()
                    ]);
            }
        }
        return response()->json([
            'msj' => "Registro actualizado exitosamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $g = Gallo::find($id);
        $gh = GallosHijo::where('hijo_id', $id)->delete();
        $g->delete();
        return response()->json([
            'msj' => "Registro eliminado exitosamente"
        ], 200);
    }

    public function search(Request $request){
        $g = Gallo::with('gallos_imagenes', 'gallos_hijos', 'gallos_hijos.padre', 'ventas')
            ->where('placa', 'like', '%' . $request->dato . '%')
            ->orWhere('marca_nacimiento', 'like', '%' . $request->dato . '%')
            ->get();
        return response()->json([
            'data' => $g
        ], 200);
    }
}
