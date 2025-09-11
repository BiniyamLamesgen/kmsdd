<?php

namespace App\Actions\Skill;

use App\Models\Skill;

class ShowSkillAction
{
    public function execute(string $id)
    {
        $skill = Skill::find($id);
        if (!$skill) {
            return ['success' => false, 'message' => 'Skill not found.'];
        }
        return ['success' => true, 'skill' => $skill];
    }
}
