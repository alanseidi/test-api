<?php

namespace App\Http\Controllers;

use App\Models\VwRelatorio;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function getRelatorio()
    {
        return VwRelatorio::all();
    }
}
