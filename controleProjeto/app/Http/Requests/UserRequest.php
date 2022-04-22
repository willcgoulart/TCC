<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo Nome é obrigatório!',
            'email.required' => 'O campo E-mail é obrigatório!',
            'email.email' => 'O campo é do tipo E-mail',
            'password.required' => 'O campo Senha é obrigatório!',
            'password.min' => 'O campo Senha deve conter 8 caracteres!',
        ];
    }
}