<?php

namespace App\Actions\Achievement;

use App\Models\Achievement;

class UpdateAchievementAction
{
    public function execute(string $id, array $data)
    {
        $achievement = Achievement::find($id);
        if (!$achievement) {
            return ['success' => false, 'message' => 'Achievement not found.'];
        }
        $achievement->update($data);
        return ['success' => true, 'achievement' => $achievement, 'message' => 'Achievement updated successfully.'];
    }
}
