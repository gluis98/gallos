<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $clientes = User::query()->get();

        return response()->json([
            'data' => $clientes,
        ], 200);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $clientes = User::query()->create($data);

        return response()->json([
            'msj' => 'Registro registrado exitosamente',
            'data' => $clientes,
        ], 200);
    }

    public function show(string $id)
    {
        $clientes = User::query()->find($id);

        return response()->json([
            'data' => $clientes,
        ], 200);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        User::query()->findOrFail($id)->fill($data)->save();

        return response()->json([
            'msj' => 'Registro actualizado exitosamente',
        ], 200);
    }

    public function destroy($id)
    {
        User::query()->findOrFail($id)->delete();

        return response()->json([
            'msj' => 'Registro eliminado exitosamente',
        ], 200);
    }
}
