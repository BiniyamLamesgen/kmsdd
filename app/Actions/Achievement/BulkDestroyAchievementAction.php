<?php

namespace App\Actions\Achievement;

use App\Models\Achievement;

class BulkDestroyAchievementAction
{
    public function execute(array $ids): array
    {
        try {
            Achievement::whereIn('id', $ids)->delete();
            return [
                'success' => true,
                'message' => 'Selected achievements deleted successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
