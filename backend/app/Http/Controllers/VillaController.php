<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::all();
        return response()->json([
            'status' => 'success',
            'data' => $villas
        ]);
    }

    public function show($slug)
    {
        $villa = Villa::where('slug', $slug)->firstOrFail();
        return response()->json([
            'status' => 'success',
            'data' => $villa
        ]);
    }
}
