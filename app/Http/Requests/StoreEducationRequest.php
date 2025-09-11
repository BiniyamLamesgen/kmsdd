<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'field_of_study' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
        ];
    }

    public function messages(): array
    {
        return [
            'graduation_year.max' => 'Graduation year cannot be more than 10 years in the future.',
            'graduation_year.min' => 'Graduation year must be after 1900.',
        ];
    }
}
