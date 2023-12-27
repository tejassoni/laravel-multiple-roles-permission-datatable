<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',                       
            'images' => 'required|array|max:10', // Limit 10 images for example
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rules max file size 2mb
            'select_parent_cat' => 'required',
            'price' => ['required', 'numeric', 'regex:/^\d*(\.\d{1,2})?$/','min:1','max:9999999999.99'], // Allows numbers and decimals with up to 2 decimal places        
            'qty' => ['required', 'numeric', 'min:1','max:4294967295'],
        ];
    }
}