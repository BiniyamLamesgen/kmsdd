<?php

namespace App\Http\Requests\HeroSlide;

use Illuminate\Foundation\Http\FormRequest;

class HeroSlideStoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required',
            'order' => 'nullable|integer',
        ];
    }
}
