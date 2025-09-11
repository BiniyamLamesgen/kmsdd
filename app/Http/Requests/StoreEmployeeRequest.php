<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'required|email|unique:employees,email|max:255',
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
            'availability_for_sharing' => 'boolean',
            'password' => 'required|string|min:6|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'internal_extension.max' => 'The internal extension field must not be greater than 10 characters.',
            'linkedin.url' => 'The linkedin field must be a valid URL.',
            'facebook.url' => 'The facebook field must be a valid URL.',
            'twitter.url' => 'The twitter field must be a valid URL.',
            'github.url' => 'The github field must be a valid URL.',
            'profile_picture.image' => 'The profile picture field must be an image.',
        ];
    }
}
