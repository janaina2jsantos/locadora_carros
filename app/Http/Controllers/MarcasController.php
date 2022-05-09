<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Http\Requests\MarcaRequest;


class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = Marca::all();
        return $marcas;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarcaRequest $request)
    {   
        $imagem  = $request->file('imagem');
        $img_urn = $imagem->store('imagens', 'public');

        $marca = Marca::create([
            'nome'   => $request->nome,
            'imagem' => $img_urn
        ]);

        return response()->json(['marca' => $marca, 'msg' => 'Marca cadastrada com sucesso!'], 200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Marca $marca)
    {
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(MarcaRequest $request, Marca $marca)
    {
        $imagem  = $request->file('imagem');
        $img_urn = $imagem->store('imagens', 'public');

        $marca->update([
            'nome'   => $request->input('nome')  ? $request->input('nome') : $marca->nome,
            'imagem' => $img_urn ? $img_urn : $marca->imagem
        ]);

        return response()->json(['marca' => $marca, 'msg' => 'Marca atualizada com sucesso!'], 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Marca $marca)
    {
        try{
            $marca->delete();
            return response()->json(['msg' => 'A marca foi excluída com sucesso!'], 200); 
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
