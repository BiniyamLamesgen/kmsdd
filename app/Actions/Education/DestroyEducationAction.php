<?php

namespace App\Actions\Education;

use App\Models\Education;

class DestroyEducationAction
{
    public function execute(string $id): array
    {
        try {
            $education = Education::findOrFail($id);
            $education->delete();
            return [
                'success' => true,
                'message' => 'Education deleted successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
