<?php

namespace App\Http\Controllers;

use App\Models\Gallo;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all()
    {
        $g = Gallo::all();
        return view('reports.all', compact('g'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
    {
        $g = Gallo::find($id);
        return view('reports.show', compact('g'));
    }

   
}
