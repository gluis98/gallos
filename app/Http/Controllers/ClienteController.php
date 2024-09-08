<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json([
            'data' => $clientes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clientes = Cliente::create($request->all());
        return response()->json([
            'msj' => "Registro registrado exitosamente"
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $clientes = Cliente::find($id);
        return response()->json([
            'data' => $clientes
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $clientes = Cliente::find($id)->fill($request->all())->save();
        return response()->json([
            'msj' => "Registro actualizado exitosamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clientes = Cliente::find($id)->delete();
        return response()->json([
            'msj' => "Registro eliminado exitosamente"
        ], 200);
    }
}
