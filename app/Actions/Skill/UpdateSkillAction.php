<?php

namespace App\Actions\Skill;

use App\Models\Skill;

class UpdateSkillAction
{
    public function execute(string $id, array $data)
    {
        $skill = Skill::find($id);
        if (!$skill) {
            return ['success' => false, 'message' => 'Skill not found.'];
        }
        $skill->update($data);
        return ['success' => true, 'skill' => $skill, 'message' => 'Skill updated successfully.'];
    }
}
