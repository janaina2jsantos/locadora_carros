<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
use App\Http\Requests\LocacaoRequest;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao) 
    {
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->has('atributos')) {
            // pegando os atributos de locacao
            $atributos = $request->atributos;  
            $locacaoRepository->selectRegistrosAtributos($atributos);
        }

        if ($request->has('atributos_cliente')) {
            // pegando os atributos do cliente (relacionamento Locacao/Cliente)
            $atributos_cliente = $request->atributos_cliente; 
            // chama o método passando o relacionamento
            $locacaoRepository->selectRegistrosAtributosRelacionados('cliente:id,'.$atributos_cliente);
        }
        else {
            $locacaoRepository->selectRegistrosAtributosRelacionados('cliente');
        }

        if ($request->has('atributos_carro')) {
            // pegando os atributos do carro (relacionamento Locacao/Carro)
            $atributos_carro = $request->atributos_carro; 
            // chama o método passando o relacionamento
            $locacaoRepository->selectRegistrosAtributosRelacionados('carro:id,'.$atributos_carro);
        }
        else {
            $locacaoRepository->selectRegistrosAtributosRelacionados('carro');
        }

        if ($request->has('filtros')) {
            $locacaoRepository->filtro($request->filtros);
        }

        return response()->json($locacaoRepository->getResultado(), 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocacaoRequest $request)
    {
        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        ]);

        return response()->json(['locacao' => $locacao, 'msg' => 'Locação cadastrada com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->with('cliente')->with('carro')->findOrFail($id);
        return $locacao;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function update(LocacaoRequest $request, $id)
    {
        $locacao = $this->locacao->findOrFail($id);

        $locacao->update([
            'cliente_id' => $request->input('cliente_id'),
            'carro_id' => $request->input('carro_id'),
            'data_inicio_periodo' => isset($request->data_inicio_periodo) ? $request->input('data_inicio_periodo') : $locacao->data_inicio_periodo,
            'data_final_previsto_periodo' => isset($request->data_final_previsto_periodo) ? $request->input('data_final_previsto_periodo') : $locacao->data_final_previsto_periodo,
            'data_final_realizado_periodo' => isset($request->data_final_realizado_periodo) ? $request->input('data_final_realizado_periodo') : $locacao->data_final_realizado_periodo,
            'valor_diaria' => isset($request->valor_diaria) ? $request->input('valor_diaria') : $locacao->valor_diaria,
            'km_inicial' => isset($request->km_inicial) ? $request->input('km_inicial') : $locacao->km_inicial,
            'km_final' => isset($request->km_final) ? $request->input('km_final') : $locacao->km_final
        ]);

        return response()->json(['locacao' => $locacao, 'msg' => 'Locação atualizada com sucesso!'], 200); 
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
            $locacao = $this->locacao->findOrFail($id);
            $locacao->delete();
            return response()->json(['msg' => 'A locação foi excluída com sucesso!'], 200);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
