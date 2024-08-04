<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Http\Resources\AssuntoResource;
use App\Models\Assunto;
use Illuminate\Http\Request;

class AssuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assuntos = Assunto::all();
        return AssuntoResource::collection($assuntos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssuntoRequest $request)
    {
        $assunto = Assunto::create($request->validated());
        return new AssuntoResource($assunto);
    }

    /**
     * Display the specified resource.
     */
    public function show(Assunto $assunto)
    {
        return new AssuntoResource($assunto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssuntoRequest $request, Assunto $assunto)
    {
        $assunto->update($request->validated());
        return new AssuntoResource($assunto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assunto $assunto)
    {
        $assunto->delete();
        return response(null, 204);
    }
}
