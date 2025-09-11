<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEndorsementRequest extends FormRequest
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
            'employee_id' => 'sometimes|required|exists:employees,id',
            'skill_id' => 'sometimes|required|exists:skills,id',
            'endorsed_by' => 'sometimes|required|exists:employees,id|different:employee_id',
            'endorsement_date' => 'nullable|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'endorsed_by.different' => 'An employee cannot endorse themselves.',
            'endorsement_date.before_or_equal' => 'Endorsement date cannot be in the future.',
        ];
    }
}
