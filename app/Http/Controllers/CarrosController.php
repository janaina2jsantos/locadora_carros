<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carro;
use App\Http\Requests\CarroRequest;
use App\Repositories\CarroRepository;

class CarrosController extends Controller
{
    private $carro;

    // Injetando a instância do Model no construtor 
    // ===============================================
    public function __construct(Carro $carro) 
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->has('atributos')) {
            // pegando os atributos de carro
            $atributos = $request->atributos;  
            $carroRepository->selectRegistrosAtributos($atributos);
        }
       
        if ($request->has('atributos_modelo')) {
            // pegando os atributos do modelo (relacionamento Carro/Modelo)
            $atributos_modelo = $request->atributos_modelo; 
            // chama o método passando o relacionamento
            $carroRepository->selectRegistrosAtributosRelacionados('modelo:id,'.$atributos_modelo);
        }
        else {
            $carroRepository->selectRegistrosAtributosRelacionados('modelo');
        }

        if ($request->has('filtros')) {
            $carroRepository->filtro($request->filtros);
        }

        return response()->json($carroRepository->getResultado(), 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarroRequest $request)
    {
        $carro = $this->carro->create([
            'modelo_id'  => $request->modelo_id,
            'placa'      => $request->placa,
            'disponivel' => $request->disponivel,
            'km'         => $request->km
        ]);

        return response()->json(['carro' => $carro, 'msg' => 'Carro cadastrado com sucesso!'], 200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->findOrFail($id);
        return $carro;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function update(CarroRequest $request, $id)
    {
        $carro = $this->carro->findOrFail($id);
        $carro->update([
            'modelo_id'  => $request->input('modelo_id'),
            'placa'      => isset($request->placa) ? $request->input('placa') : $carro->placa,
            'disponivel' => isset($request->disponivel) ? $request->input('disponivel') : $carro->disponivel,
            'km'         => isset($request->km) ? $request->input('km') : $carro->km
        ]);

        return response()->json(['carro' => $carro, 'msg' => 'Carro atualizado com sucesso!'], 200); 
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
            $carro = $this->carro->findOrFail($id);
            $carro->delete();
            return response()->json(['msg' => 'O carro foi excluído com sucesso!'], 200);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
