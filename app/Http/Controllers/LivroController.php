<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivroRequest;
use App\Http\Resources\LivroResource;
use App\Models\Livro;
use App\Services\LivroAssuntoService;
use App\Services\LivroAutorService;
use Illuminate\Http\Request;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $livros = Livro::paginate(20);
        return LivroResource::collection($livros);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LivroRequest $request)
    {
        $livro = Livro::create($request->validated());
        return new LivroResource($livro);
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro)
    {
        return new LivroResource($livro);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LivroRequest $request, Livro $livro)
    {
        $livro->update($request->validated());
        return new LivroResource($livro);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro)
    {
        $livro->delete();
        return response(null, 204);
    }

    public function connectAutor(Request $request)
    {
        $request->validate([
            'codL' => 'required|numeric|exists:livro,codL',
            'arrayCodAu' => 'array',
            'arrayCodAu.*' => 'required|numeric|exists:autor,codAu',
        ]);
        $data = $request->all();
        $livroAutorService = new LivroAutorService();
        $livro = $livroAutorService->syncLivroAutor($data);
        return new LivroResource($livro);
    }

    public function connectAssunto(Request $request)
    {
        $request->validate([
            'codL' => 'required|numeric|exists:livro,codL',
            'arrayCodAs' => 'array',
            'arrayCodAs.*' => 'required|numeric|exists:assunto,codAs',
        ]);
        $data = $request->all();
        $livroAssuntoService = new LivroAssuntoService();
        $livro = $livroAssuntoService->syncLivroAssunto($data);
        return new LivroResource($livro);
    }
}
