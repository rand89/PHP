<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'role' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Это обязательное поле',
            'name.string' => 'Это должна быть строка',
            'email.required' => 'Это обязательное поле',
            'email.email' => 'Это должен быть email',
            'email.unique' => 'Такой email уже занят'
        ];
    }
}
