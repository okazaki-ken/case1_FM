<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderMessageRequest extends FormRequest
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
    public function rules(){
        return [
            'body'  => ['nullable', 'string', 'max:400'],
            'image' => ['nullable', 'file', 'mimes:png,jpeg,jpg'],
        ];
    }

    public function messages(): array{
        return [
            'body.max'      => '本文は400文字以内で入力してください',
            'image.mimes'   => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }

    public function withValidator($validator){

        $validator->after(function ($validator) {
            $hasBody  = trim((string)$this->body) !== '';
            $hasImage = $this->hasFile('image');

            if (!$hasBody && !$hasImage) {
                $validator->errors()->add('body', '本文を入力してください。');
            }

            if ($hasBody && $hasImage) {
                $validator->errors()->add('body', 'テキストと画像は同時に送信できません。');
            }
        });
    }
}
