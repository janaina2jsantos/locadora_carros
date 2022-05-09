<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeloRequest extends FormRequest
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
        $id = $this->route()->parameter('modelo');

        return [
            'marca_id' => 'exists:marcas,id',
            'nome'     => (!$id ? 'required|min:3|unique:modelos,nome' : 'min:3|unique:modelos,nome,'.$id.',id'),
            'imagem'   => (!$id ? 'required|file|mimes:jpeg,png,jpg,gif' : 'file|mimes:jpeg,png,jpg,gif'),
            'numero_portas' => (!$id ? 'required|integer|digits_between:1,5' : ''),
            'lugares' => (!$id ? 'required|integer|digits_between:1,20' : ''),
            'air_bag' => (!$id ? 'required|boolean' : ''),
            'abs'     => (!$id ? 'required|boolean' : '')
        ];
    }

    public function messages()
    {
        return [
            'required'     => 'O campo :attribute é obrigatório.',
            'marca_id.exists' => 'O campo marca_id é obrigatório.',
            'nome.unique'  => 'Este nome já está cadastrado.',
            'nome.min'     => 'O nome do modelo deve conter no mínimo 3 caracteres.',
            'imagem.mimes' => 'Arquivo inválido. Selecione um arquivo do tipo imagem.',
            'integer'      => 'O campo :attribute deve ser um número inteiro.',
            'numero_portas.digits_between' => 'O número de portas deve ser entre 1 e 5.',
            'lugares.digits_between' => 'O número de lugares deve ser entre 1 e 20.',
            'air_bag.boolean' => 'O campo airbag deve ser 1 ou 0.',
            'abs.boolean' => 'O campo abs deve ser 1 ou 0.'
        ];
    }
}
