<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Http\Requests\MarcaRequest;
use Illuminate\Support\Facades\Storage;


class MarcasController extends Controller
{
    private $marca;

    public function __construct(Marca $marca) 
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->with('modelos')->get();
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
        $img_urn = $imagem->store('imagens/marcas', 'public');

        $marca = $this->marca->create([
            'nome'   => $request->nome,
            'imagem' => $img_urn
        ]);

        return response()->json(['marca' => $marca, 'msg' => 'Marca cadastrada com sucesso!'], 200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->findOrFail($id);
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(MarcaRequest $request, $id)
    {
        $marca = $this->marca->findOrFail($id);

        // remove a imagem antiga caso uma nova imagem tenha sido enviada no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem  = $request->file('imagem');
        $img_urn = isset($imagem ) ? $imagem->store('imagens/marcas', 'public') : $marca->imagem;

        $marca->update([
            'nome'   => isset($request->nome) ? $request->input('nome') : $marca->nome,
            'imagem' => $img_urn 
        ]);

        return response()->json(['marca' => $marca, 'msg' => 'Marca atualizada com sucesso!'], 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $marca = $this->marca->findOrFail($id);
            // remove a imagem associada à marca
            Storage::disk('public')->delete($marca->imagem);
            // remove a marca
            $marca->delete();
            return response()->json(['msg' => 'A marca foi excluída com sucesso!'], 200); 
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
