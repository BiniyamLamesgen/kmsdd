<?php

namespace App\Http\Requests\HeroSlide;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteHeroSlideRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:hero_slides,id',
        ];
    }

    public function messages(): array {
        return [
            'ids.required' => 'At least one hero slide must be selected for deletion.',
            'ids.array' => 'Invalid data format for bulk deletion.',
            'ids.min' => 'At least one hero slide must be selected.',
            'ids.*.required' => 'Each selected item must have a valid ID.',
            'ids.*.integer' => 'Invalid hero slide ID format.',
            'ids.*.exists' => 'One or more selected hero slides do not exist.',
        ];
    }
}
