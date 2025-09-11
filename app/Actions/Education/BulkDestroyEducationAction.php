<?php

namespace App\Actions\Education;

use App\Models\Education;

class BulkDestroyEducationAction
{
    public function execute(array $ids): array
    {
        try {
            $count = Education::whereIn('id', $ids)->delete();
            return [
                'success' => $count > 0,
                'message' => $count > 0 ? 'Education records deleted successfully.' : 'No education records deleted.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
