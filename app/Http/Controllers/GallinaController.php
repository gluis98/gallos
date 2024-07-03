<?php

namespace App\Http\Controllers;

use App\Models\Gallina;
use App\Models\GallinasImagene;
use Illuminate\Http\Request;

class GallinaController extends Controller
{
    public function index()
    {
        $g = Gallina::all();
        return response()->json([
            'data' => $g
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $g = Gallina::create($request->all())->latest('id')->first();

        if($request->hasFile('file')){
            foreach($request->file('file') as $file){
                $name = $file->getClientOriginalName();
                $file->move(public_path('files/' . $request->placa . '/'), $name);
                $gi = GallinasImagene::create([
                                'gallina_id' => $g->id, 
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
        $g = Gallina::find($id);
        return response()->json([
            'data' => $g
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $g = Gallina::find($id)->fill($request->all())->save();
        return response()->json([
            'msj' => "Registro actualizado exitosamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $g = Gallina::find($id)->delete();
        return response()->json([
            'msj' => "Registro eliminado exitosamente"
        ], 200);
    }
}
