<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarcaRequest extends FormRequest
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
        // id do registro que será desconsiderado na pesquisa do unique
        $id = $this->route()->parameter('marca');
        
        return [
            'nome'   => (!$id ? 'required|min:3|unique:marcas,nome' : 'min:3|unique:marcas,nome,'.$id.',id'),
            'imagem' => (!$id ? 'required|file|mimes:jpeg,png,jpg,gif' : 'file|mimes:jpeg,png,jpg,gif')
        ];
    }


    public function messages()
    {
        return [
            'required'     => 'O campo :attribute é obrigatório.',
            'nome.unique'  => 'Este nome já está cadastrado.',
            'nome.min'     => 'O nome da marca deve conter no mínimo 3 caracteres.',
            'imagem.mimes' => 'Arquivo inválido. Selecione um arquivo do tipo imagem.'
        ];
    }
}
