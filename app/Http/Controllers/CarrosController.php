<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carro;


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
    public function index()
    {
        $carros = $this->carro->all();
        return $carros;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $carro = $this->carro->findOrFail($id);
        return $carro;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->findOrFail($id);
        $carro->update([
            'modelo_id'  => $request->input('modelo_id'),
            'placa'      => $request->input('placa'),
            'disponivel' => $request->input('disponivel'),
            'km'         => $request->input('km')
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
        $carro = $this->carro->findOrFail($id);
        $carro->delete();
        return response()->json(['msg' => 'O carro foi excluído com sucesso!'], 200); 
    }
}
