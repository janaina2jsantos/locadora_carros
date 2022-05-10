<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Http\Requests\ModeloRequest;
use Illuminate\Support\Facades\Storage;


class ModelosController extends Controller
{
    private $modelo;

    public function __construct(Modelo $modelo) 
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelos = [];

        if ($request->has('atributos')) {
            // pegando atributos do modelo
            $atributos = $request->atributos;
            // iniciando a montagem da query builder
            // o selectRaw faz um bind de valores na query builder.
            $modelos = $this->modelo->selectRaw($atributos);
        }
        else {
            $modelos = $this->modelo->select();
        }

        if ($request->has('atributos_marca')) {
            // pegando atributos do relacionamento de modelo com marcas
            $atributos_marca = $request->atributos_marca; 
            $modelos = $modelos->with('marca:id,'.$atributos_marca);
        }
        else {
            $modelos = $modelos->with('marca');
        }

        if ($request->has('filtros')) {
            $filtros = explode(';', $request->filtros);
            foreach($filtros as $key => $filtro) {
                $f = explode(':', $filtro);
                $modelos = $modelos->where($f[0], $f[1], $f[2]);
            }
        }

        // finalizando a query
        $modelos = $modelos->get();
        return $modelos;

        // Ex: url com atributos e filtros
        // localhost:8000/api/modelos?atributos=nome,imagem,abs,marca_id&atributos_marca=nome,imagem&filtros=nome:like:%Toyota%;abs:=:0
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModeloRequest $request)
    {
        $imagem  = $request->file('imagem');
        $img_urn = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $img_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json(['modelo' => $modelo, 'msg' => 'Modelo cadastrado com sucesso!'], 200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->findOrFail($id);
        return $modelo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function update(ModeloRequest $request, $id)
    {
        $modelo = $this->modelo->findOrFail($id);

        // remove a imagem antiga caso uma nova imagem tenha sido enviada no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $imagem  = $request->file('imagem');
        $img_urn = isset($imagem ) ? $imagem->store('imagens/modelos', 'public') : $modelo->imagem;

        $modelo->update([
            'marca_id' => $request->input('marca_id'),
            'nome' => isset($request->nome) ? $request->input('nome') : $modelo->nome,
            'imagem' => $img_urn,
            'numero_portas' => isset($request->numero_portas) ? $request->input('numero_portas') : $modelo->numero_portas,
            'lugares' => isset($request->lugares) ? $request->input('lugares') : $modelo->lugares,
            'air_bag' => isset($request->air_bag) ? $request->input('air_bag') : $modelo->air_bag,
            'abs' => isset($request->abs) ? $request->input('abs') : $modelo->abs
        ]);

        return response()->json(['modelo' => $modelo, 'msg' => 'Modelo atualizado com sucesso!'], 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $modelo = $this->modelo->findOrFail($id);
            // remove a imagem associada ao modelo
            Storage::disk('public')->delete($modelo->imagem);
            // remove o modelo
            $modelo->delete();
            return response()->json(['msg' => 'O modelo foi excluído com sucesso!'], 200); 
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
