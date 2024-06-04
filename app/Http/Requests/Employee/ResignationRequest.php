<?php

namespace App\Http\Requests\Employee;

use GlobalXtreme\Validation\Support\FormRequest;

class ResignationRequest extends FormRequest
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
            'date' => 'required|date',
            'reason' => 'required',
            'file' => 'required|mimes:pdf|max:2048'
        ];
    }
}
