<?php

namespace App\Actions\Education;

use App\Models\Education;

class EditEducationAction
{
    public function execute(string $id): array
    {
        try {
            $education = Education::findOrFail($id);
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
