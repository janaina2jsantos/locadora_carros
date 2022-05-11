<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarroRequest extends FormRequest
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
        $id = $this->route()->parameter('carro');
        return [
            'modelo_id'  => 'exists:modelos,id',
            'placa'      => (!$id ? 'required|min:3' : 'min:3'),
            'disponivel' => (!$id ? 'required|boolean' : 'boolean'),
            'km'         => (!$id ? 'required|integer' : 'integer')
        ];
    }

    public function messages()
    {
        return [
            'required'           => 'O campo :attribute é obrigatório.',
            'modelo_id.exists'   => 'O modelo informado não existe.',
            'placa.min'          => 'O nome da placa deve conter no mínimo 3 caracteres.',
            'disponivel.boolean' => 'O campo disponível deve ser 1 ou 0.',
            'integer'            => 'O campo :attribute deve ser um número inteiro.'
        ];
    }
}
