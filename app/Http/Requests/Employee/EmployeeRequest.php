<?php

namespace App\Http\Requests\Employee;

use GlobalXtreme\Validation\Support\FormRequest;

class EmployeeRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'companyOfficeId' => 'required|integer|exists:company_offices,id',
            'departmentId' => 'required|integer|exists:departments,id',
            'photo' => 'required|mimes:png,jpg|max:2048',
            'fatherName' => 'required|string|max:255',
            'motherName' => 'required|string|max:255',
            'siblings.*.name' => 'required|string|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string';
            $rules['email'] =  'required|string|email|max:255';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'] = 'sometimes|string|email|max:255';
            $rules['password'] = 'sometimes|string|min:8';
            $rules['photo'] = 'sometimes|mimes:png,jpg|max:2048';
        }

        return $rules;
    }
}
