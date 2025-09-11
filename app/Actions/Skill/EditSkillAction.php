<?php

namespace App\Actions\Skill;

use App\Models\Skill;

class EditSkillAction
{
    public function execute($id): array
    {
        try {
            $skill = Skill::findOrFail($id);
            return [
                'success' => true,
                'skill' => $skill,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
