<?php

namespace App\Actions\Achievement;

use App\Models\Achievement;

class DestroyAchievementAction
{
    public function execute($id): array
    {
        try {
            $achievement = Achievement::findOrFail($id);
            $achievement->delete();
            return [
                'success' => true,
                'message' => 'Achievement deleted successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
