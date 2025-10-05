<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
    public function rules()    {
        return [            
            'post' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required',
            'redirect' => 'nullable|string'
        ];
    }

    public function messages(){
        return [
            'post.required' => '郵便番号を入力してください',
            'post.regex' => '郵便番号は-を含む8桁で入力してください',
            'address.required' => '住所の入力をしてください',            
        ];
    }
    
}
