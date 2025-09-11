<?php

namespace App\Actions\Achievement;

use App\Models\Achievement;

class ShowAchievementAction
{
    public function execute($id): array
    {
        try {
            $achievement = Achievement::with('employee')->findOrFail($id);
            return [
                'success' => true,
                'achievement' => $achievement,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
