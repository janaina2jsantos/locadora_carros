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
        if ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            // id do registro que será desconsiderado na pesquisa do unique
            $id = $this->route()->parameter('marca')->id;
            $name_rules  = 'unique:marcas,nome,'.$id.',id'; 
            $image_rules = 'file|mimes:jpeg,png,jpg,gif';
        }
        else {
            $name_rules  = 'required|unique:marcas,nome';
            $image_rules = 'required|file|mimes:jpeg,png,jpg,gif';
        }

        return [
            'nome'   => $name_rules,
            'imagem' => $image_rules
        ];
    }

    public function messages()
    {
        return [
            'required'     => 'O campo :attribute é obrigatório.',
            'nome.unique'  => 'O nome da marca já existe.',
            'imagem.mimes' => 'Arquivo inválido. Selecione um arquivo do tipo imagem.'
        ];
    }
}
