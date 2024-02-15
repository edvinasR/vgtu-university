<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Auth;

define('ITEMS_PER_PAGE',30);

class CoffeListRequest extends FormRequest
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
        return [];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    public function getData()
    {
        return parent::all();
    }
    /**
     * Return how many items per page needs to be returned to client
     *
     * @return integer
    */
    public function getPagination()
    {
        $perPage = $this->query('per_page');
        return $perPage == null ? ITEMS_PER_PAGE : $perPage;
    }
}
