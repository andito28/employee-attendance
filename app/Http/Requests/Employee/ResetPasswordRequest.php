<?php

namespace App\Http\Requests\Employee;

use GlobalXtreme\Validation\Support\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'existingPassword' => [
                'required',
            ],
            'newPassword' => [
                'required',
                'string',
                'min:8',
            ],
            'confirmPassword' => [
                'required',
                'same:newPassword',
            ],
        ];

    }
}
