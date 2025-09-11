<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'skill_id' => 'required|exists:skills,id',
            'proficiency_level' => 'nullable|string|in:Beginner,Intermediate,Advanced,Expert',
            'endorsements_count' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'proficiency_level.in' => 'Proficiency level must be one of: Beginner, Intermediate, Advanced, Expert.',
        ];
    }
}
