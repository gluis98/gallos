<?php

namespace App\Http\Controllers;

use App\Models\Gallo;
use App\Models\Gallina;
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

    public function allGallinas()
    {   
        $g = Gallina::all();
        return view('reports.all-gallinas', compact('g'));
    }

    public function showGallina($id)
    {
        $g = Gallina::find($id);
        return view('reports.show-gallina', compact('g'));
    }

   
}
