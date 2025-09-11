<?php

namespace App\Actions\Skill;

use App\Models\Skill;

class DestroySkillAction
{
    public function execute(string $id)
    {
        $skill = Skill::find($id);
        if (!$skill) {
            return ['success' => false, 'message' => 'Skill not found.'];
        }
        $skill->delete();
        return ['success' => true, 'message' => 'Skill deleted successfully.'];
    }
}
