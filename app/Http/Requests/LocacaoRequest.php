<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocacaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route()->parameter('locaco');
        return [
            'cliente_id' => 'exists:clientes,id',
            'carro_id'   => 'exists:carros,id',
            'data_inicio_periodo' => (!$id ? 'required|date' : 'date'),
            'data_final_previsto_periodo' => (!$id ? 'required|date' : 'date'),
            'data_final_realizado_periodo' => (!$id ? 'required|date' : 'date'),
            'valor_diaria' => (!$id ? 'required|integer' : 'integer'),
            'km_inicial' => (!$id ? 'required|integer' : 'integer'),
            'km_final' => (!$id ? 'required|integer' : 'integer')
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'date' => 'O campo :attribute deve estar no formato data.',
            'cliente_id.exists' => 'O cliente informado não existe.',
            'carro_id.exists' => 'O carro informado não existe.'
        ];
    }
}
