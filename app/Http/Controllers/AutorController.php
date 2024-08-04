<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorRequest;
use App\Http\Resources\AutorResource;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Autor::all();
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
}
