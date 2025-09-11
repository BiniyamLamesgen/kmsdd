<?php

namespace App\Actions\Education;

use App\Models\Education;

class UpdateEducationAction
{
    public function execute($id, array $data): array
    {
        try {
            $education = Education::findOrFail($id);
            $education->update($data);
            return [
                'success' => true,
                'education' => $education,
                'message' => 'Education updated successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
