<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Gallo;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index()
    {
        $v = Venta::with('cliente', 'gallo')->get();
        return response()->json([
            'data' => $v
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Venta::create($request->all());

        $g = Gallo::find($request->gallo_id);
        $g->estatus = "Vendido";
        $g->save();
        return response()->json([
            'msj' => "Registro registrado exitosamente"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $v = Venta::with('cliente', 'gallo')->find($id);
        return response()->json([
            'data' => $v
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $v = Venta::find($id)->fill($request->all())->save();
        return response()->json([
            'msj' => "Registro actualizado exitosamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $v = Venta::find($id)->delete();
        return response()->json([
            'msj' => "Registro eliminado exitosamente"
        ], 200);
    }
}
