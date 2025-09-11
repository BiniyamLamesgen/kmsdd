<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
        $employeeId = is_object($this->route('employee')) ? $this->route('employee')->id : $this->route('employee');
        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('employees')->ignore($employeeId),
                'max:255'
            ],
            'phone' => 'nullable|string|max:20',
            'internal_extension' => 'nullable|string|max:10',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'date_joined' => 'nullable|date',
            'linkedin' => 'nullable|url|max:500',
            'facebook' => 'nullable|url|max:500',
            'twitter' => 'nullable|url|max:500',
            'github' => 'nullable|url|max:500',
            'personal_website' => 'nullable|url|max:500',
            'languages' => 'nullable|string|max:500',
            'mentoring_interest' => 'nullable|string',
            'availability_for_sharing' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name field is required.',
            'last_name.required' => 'The last name field is required.',
            'position.required' => 'The position field is required.',
            'department.max' => 'The department field may not be greater than :max characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'email.max' => 'The email may not be greater than :max characters.',
            'phone.max' => 'The phone may not be greater than :max characters.',
            'internal_extension.max' => 'The internal extension may not be greater than :max characters.',
            'profile_picture.image' => 'The profile picture field must be an image.',
            'profile_picture.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg, gif, svg.',
            'date_joined.date' => 'The date joined is not a valid date.',
            'linkedin.url' => 'The linkedin format is invalid.',
            'facebook.url' => 'The facebook format is invalid.',
            'twitter.url' => 'The twitter format is invalid.',
            'github.url' => 'The github format is invalid.',
            'personal_website.url' => 'The personal website format is invalid.',
            'languages.max' => 'The languages may not be greater than :max characters.',
            'mentoring_interest.string' => 'The mentoring interest must be a string.',
            'availability_for_sharing.boolean' => 'The availability for sharing must be true or false.',
        ];
    }
}
