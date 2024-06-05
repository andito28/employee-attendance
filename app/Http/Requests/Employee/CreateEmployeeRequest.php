<?php

namespace App\Http\Requests\Employee;

use GlobalXtreme\Validation\Support\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
            'companyOfficeId' => 'required|integer|exists:company_offices,id',
            'departmentId' => 'required|integer|exists:departments,id',
            'photo' => 'required|mimes:png,jpg|max:2048',
            'fatherName' => 'required|string|max:255',
            'motherName' => 'required|string|max:255',
            'siblings.*.name' => 'required|string|max:255',
        ];
    }
}
