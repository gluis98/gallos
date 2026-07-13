<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('faq.index');
    }

    public function vacunacion(): View
    {
        return view('faq.vacunacion');
    }

    public function historialMedico(): View
    {
        return view('faq.historial-medico');
    }

    public function crianzaGallos(): View
    {
        return view('faq.crianza-gallos');
    }
}
