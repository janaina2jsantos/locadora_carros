<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Http\Requests\MarcaRequest;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;
use App\Http\Controllers\Controller;

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
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos')) {
            // pegando os atributos de marca
            $atributos = $request->atributos;  
            $marcaRepository->selectRegistrosAtributos($atributos);
        }
       
        if ($request->has('atributos_modelo')) {
            // pegando os atributos do modelo (relacionamento Marca/Modelo)
            $atributos_modelo = $request->atributos_modelo; 
            // chama o método passando o relacionamento
            $marcaRepository->selectRegistrosAtributosRelacionados('modelos:id,'.$atributos_modelo);
        }
        else {
            $marcaRepository->selectRegistrosAtributosRelacionados('modelos');
        }

        if ($request->has('filtros')) {
            $marcaRepository->filtro($request->filtros);
        }

        return response()->json($marcaRepository->getResultadoPaginado(5), 200); 
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
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function show(Marca $marca)
    {
        return $marca->with('modelos')->find($marca);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function update(MarcaRequest $request, $id)
    {
        try{
            $marca = $this->marca->findOrFail($id);
            // remove a imagem antiga caso uma nova imagem seja enviada no request
            if ($request->file('imagem')) {
                Storage::disk('public')->delete($marca->imagem);
                $imagem  = $request->file('imagem');
                $img_urn = $imagem->store('imagens/marcas', 'public');
            }

            \DB::beginTransaction();
            $marca->update([
                'nome'   => isset($request->nome) ? $request->input('nome') : $marca->nome,
                'imagem' => isset($img_urn) ? $img_urn : $marca->imagem
            ]);
            
            \DB::commit();
            return response()->json(['marca' => $marca, 'msg' => 'Marca atualizada com sucesso!'], 200); 
        }
        catch(\Exception $e) {
            \DB::rollback();
            return \Response::json(array(
                'status' => 500,
                'erro'   => 'Não foi possível atualizar o cadastro. ' . $e->getMessage()
            ), 500);
        }
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
            $marca = $this->marca->findOrFail($id);
            // remove a imagem associada à marca
            Storage::disk('public')->delete($marca->imagem);
            \DB::beginTransaction();
            // remove a marca
            $marca->delete();
            \DB::commit();
            return response()->json(['msg' => 'A marca foi excluída com sucesso!'], 200); 
        }
        catch(\Exception $e) {
            \DB::rollback();
            return \Response::json(array(
                'status' => 500,
                'erro'   => 'Não foi possível excluir o cadastro. ' . $e->getMessage()
            ), 500);
        }
    }
}
