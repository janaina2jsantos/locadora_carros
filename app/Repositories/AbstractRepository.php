<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;


abstract class AbstractRepository 
{
	public function __construct(Model $model) 
    {
        $this->model = $model;
    }

    public function selectRegistrosAtributos($atributos)
    {
        // a query está sendo montada
        $this->model = $this->model->selectRaw($atributos);
    }

    public function selectRegistrosAtributosRelacionados($atributos)
    { 
        // a query está sendo montada
        $this->model = $this->model->with($atributos);
    }

    public function filtro($filtros)
    { 
        $filtros = explode(';', $filtros);
        foreach($filtros as $key => $filtro) {
            $f = explode(':', $filtro);
            // a query está sendo montada
            $this->model = $this->model->where($f[0], $f[1], $f[2]);
        }
    }

    public function getResultado()
    {   
        // finalizando a query
        return $this->model->get();
    }

    public function getResultadoPaginado($numero)
    {   
        // finalizando a query
        return $this->model->paginate($numero);
    }
}