<?php

namespace App\Http\Requests;

use App\Models\Diary;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Diary $diary */
        $diary = $this->route('diary');

        return $this->user()->can('update', $diary);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Diary $diary */
        $diary = $this->route('diary');

        return [
            'body' => ['required', 'string', 'max:140'],
            'diary_date' => [
                'required',
                'date',
                function (string $attribute, mixed $value, \Closure $fail) use ($diary): void {
                    $exists = $this->user()->diaries()
                        ->whereDate('diary_date', $value)
                        ->where('id', '!=', $diary->id)
                        ->exists();

                    if ($exists) {
                        $fail('この日付の日記は既に登録されています。');
                    }
                },
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg', 'max:2048'],
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
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像は jpg, jpeg 形式のみアップロードできます。',
            'image.max' => '画像は2MB以下にしてください。',
        ];
    }
}
