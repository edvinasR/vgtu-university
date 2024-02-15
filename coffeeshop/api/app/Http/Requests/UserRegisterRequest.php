<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|max:255|min:7',
            'confirm_password' => 'required|max:255|same:password',
            'name' => 'required|max:255',
        ];
    }

    public function getData()
    {
        return parent::all();
    }
}
