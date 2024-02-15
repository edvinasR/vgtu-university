<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Auth;

class CoffeeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'max:255|string',
            'image' => 'image|mimes:jpeg,png|max:2048',
            'price' => 'regex:/^\d+(\.\d{1,2})?$/|numeric|max:1000',
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price.regex' => 'Price format is invalid!',
        ];
    }

    public function getData()
    {
        $coffeeData = parent::all();
        $coffeeData['user_id'] = $this->user()->id;
        return $coffeeData;
    }
}
