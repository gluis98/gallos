<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Client::query()->get();

        return response()->json([
            'data' => $clientes,
        ], 200);
    }

    public function store(StoreClientRequest $request)
    {
        $clientes = Client::query()->create($request->validated());

        return response()->json([
            'msj' => 'Registro registrado exitosamente',
            'data' => $clientes,
        ], 200);
    }

    public function show(string $id)
    {
        $clientes = Client::query()->find($id);

        return response()->json([
            'data' => $clientes,
        ], 200);
    }

    public function update(UpdateClientRequest $request, $id)
    {
        Client::query()->findOrFail($id)->fill($request->validated())->save();

        return response()->json([
            'msj' => 'Registro actualizado exitosamente',
        ], 200);
    }

    public function destroy($id)
    {
        Client::query()->findOrFail($id)->delete();

        return response()->json([
            'msj' => 'Registro eliminado exitosamente',
        ], 200);
    }
}
