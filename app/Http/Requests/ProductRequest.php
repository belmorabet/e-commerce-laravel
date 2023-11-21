<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('products', 'title')->ignore($this->product)],
            'slug' => [Rule::unique('products', 'slug')->ignore($this->product)],
            'image' => 'nullable|image',
            'in_stock_quantity' => 'required|integer|min:0',
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'description' => 'required',
            'price' => 'required|numeric|min:0|max:999',
            'dimensions' => 'required|string|max:255',
            'type' => 'required|string|max:255'
        ];
    }
}
