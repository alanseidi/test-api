<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssuntoRequest;
use App\Http\Resources\AssuntoResource;
use App\Http\Resources\AutorResource;
use App\Models\Assunto;
use App\Services\LivroAssuntoService;
use App\Services\LivroAutorService;
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

    public function connectLivro(Request $request)
    {
        $request->validate([
            'codAs' => 'required|numeric|exists:assunto,codAs',
            'arrayCodL' => 'required|array',
            'arrayCodL.*' => 'required|numeric|exists:livro,codL',
        ]);
        $data = $request->all();
        $livroAssuntoService = new LivroAssuntoService();
        $assunto = $livroAssuntoService->syncAssuntoLivro($data);
        return new AssuntoResource($assunto);
    }
}
