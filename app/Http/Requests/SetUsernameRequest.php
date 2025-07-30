<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetUsernameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
         'username' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:users,username'],
        ];
        
    }

    public function messages(): array
    {
        return [
            'username.required' => 'ユーザー名は必須です。',
            'username.alpha_dash' => 'ユーザー名は英数字とダッシュ（_）のみ使用できます。',
            'username.unique' => 'このユーザー名は既に使用されています。',
        ];
    }
}
