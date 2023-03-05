<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductFormRequest extends FormRequest
{
    /**
     * This laravel form request file will handle the validation for product data(food menu)
     * whenever complex type validation logic is required, we use laravel form request
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
            'name'               =>  'required|max:255',
            'price'              =>  'required|regex:/^\d+(\.\d{1,2})?$/',
            'discount_price'     =>  'regex:/^\d+(\.\d{1,2})?$/',            
        ];
    }
}
