<?php

namespace App\Http\Controllers\API;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteRequest;
use App\Repositories\ClienteRepository;
use App\Http\Controllers\Controller;

class ClientesController extends Controller
{
    public function __construct(Cliente $cliente) 
    {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if ($request->has('atributos')) {
            // pegando os atributos de cliente
            $atributos = $request->atributos;  
            $clienteRepository->selectRegistrosAtributos($atributos);
        }

        if ($request->has('filtros')) {
            $clienteRepository->filtro($request->filtros);
        }

        return response()->json($clienteRepository->getResultado(), 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClienteRequest $request)
    {
        $cliente = $this->cliente->create([
            'nome' => $request->nome
        ]);

        return response()->json(['cliente' => $cliente, 'msg' => 'Cliente cadastrado com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->findOrFail($id);
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \integer
     * @return \Illuminate\Http\Response
     */
    public function update(ClienteRequest $request, $id)
    {
        $cliente = $this->cliente->findOrFail($id);
        $cliente->update([
            'nome' => isset($request->nome) ? $request->input('nome') : $cliente->nome
        ]);

        return response()->json(['cliente' => $cliente, 'msg' => 'Cliente atualizado com sucesso!'], 200); 
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
            $cliente = $this->cliente->findOrFail($id);
            $cliente->delete();
            return response()->json(['msg' => 'O cliente foi excluído com sucesso!'], 200);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
