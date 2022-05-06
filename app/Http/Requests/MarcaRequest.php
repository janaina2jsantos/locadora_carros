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
        $id = $this->route()->parameter('marca')->id;

        if ($this->method() == 'PUT') {
            $name_rules  = 'required|unique:marcas,nome,'.$id.',id';
            $image_rules = 'required|mimes:jpeg,png,jpg,gif,svg';
        }
        else if($this->method() == 'PATCH') {
            $name_rules  = 'unique:marcas,nome,'.$id.',id'; 
            $image_rules = 'mimes:jpeg,png,jpg,gif,svg';
        }
        else {
            $name_rules  = 'required|unique:marcas,nome';
            $image_rules = 'required|mimes:jpeg,png,jpg,gif,svg';
        }

        return [
            'nome'   => $name_rules,
            'imagem' => $image_rules
        ];
    }

    public function messages()
    {
        return [
            'required'    => 'O campo :attribute é obrigatório.',
            'nome.unique' => 'O nome da marca já existe.',
            'image.mimes' => 'Esse arquivo não é válido.',
        ];
    }
}
