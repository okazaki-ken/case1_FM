<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|max:20',            
            'post' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required',
            'building' => 'nullable',
            'profile_image' => 'nullable|image|mimes:jpg,png',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前の入力は20文字以内です',
            'post.required' => '郵便番号を入力してください',
            'post.regex' => '郵便番号は-を含む8桁で入力してください',
            'address.required' => '住所の入力をしてください',
            'profile_image.image' => '画像ファイルを選択してください',
            'profile_image.mimes'=> '画像ファイルはjpegまたはpngのみです',
        ];
    }
}
