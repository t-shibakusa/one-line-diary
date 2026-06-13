<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'body' => ['required', 'string', 'max:140'],
            'diary_date' => [
                'required',
                'date',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $exists = $this->user()->diaries()
                        ->whereDate('diary_date', $value)
                        ->exists();

                    if ($exists) {
                        $fail('この日付の日記は既に登録されています。');
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください。',
            'body.max' => '本文は140文字以内で入力してください。',
            'diary_date.required' => '日付を入力してください。',
            'diary_date.date' => '日付の形式が正しくありません。',
            'diary_date.unique' => 'この日付の日記は既に登録されています。',
        ];
    }
}
