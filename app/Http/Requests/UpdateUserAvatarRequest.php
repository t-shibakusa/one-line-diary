<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'avatar.required' => '画像ファイルを選択してください。',
            'avatar.image' => '画像ファイルを選択してください。',
            'avatar.mimes' => '画像は jpg, jpeg, png, webp 形式のみアップロードできます。',
            'avatar.max' => '画像は2MB以下にしてください。',
        ];
    }
}
