<?php

namespace App\Actions\Education;

use App\Models\Education;

class ShowEducationAction
{
    public function execute($id): array
    {
        try {
            $education = Education::with('employee')->findOrFail($id);
            return [
                'success' => true,
                'education' => $education,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
