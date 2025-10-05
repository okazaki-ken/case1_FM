<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'item_image' => 'required|image|mimes:jpg,png',
            'type' => 'required',
            'condition' => 'required',
            'name' => 'required',
            'explanation' => 'required|max:255',
            'price' => 'required|integer|min:0',            
        ];
    }
}
