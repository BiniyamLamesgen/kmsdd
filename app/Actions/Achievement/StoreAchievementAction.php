<?php

namespace App\Actions\Achievement;
use App\Models\Achievement;


class StoreAchievementAction
{
    public function execute(array $data): array
    {
        try {
            $achievement = Achievement::create($data);
            return [
                'success' => true,
                'message' => 'Achievement created successfully.',
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
