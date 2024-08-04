<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Http\Resources\AutorResource;
use App\Http\Resources\LivroResource;
use App\Models\Autor;
use App\Services\LivroAutorService;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Autor::paginate(20);
        return AutorResource::collection($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AutorRequest $request)
    {
        $autor = Autor::create($request->validated());
        return new AutorResource($autor);
    }

    /**
     * Display the specified resource.
     */
    public function show(Autor $autor)
    {
        return new AutorResource($autor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AutorRequest $request, Autor $autor)
    {
        $autor->update($request->validated());
        return new AutorResource($autor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Autor $autor)
    {
        $autor->delete();
        return response(null, 204);
    }


    public function connectLivro(Request $request)
    {
        $request->validate([
            'codAu' => 'required|numeric|exists:autor,codAu',
            'arrayCodL' => 'array',
            'arrayCodL.*' => 'required|numeric|exists:livro,codL',
        ]);
        $data = $request->all();
        $livroAutorService = new LivroAutorService();
        $autor = $livroAutorService->syncAutorLivro($data);
        return new AutorResource($autor);
    }
}
