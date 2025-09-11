<?php

namespace App\Actions\Skill;

use App\Models\Skill;

class StoreSkillAction
{
    public function execute(array $data)
    {
        $skill = new CreateSkillAction();
        try {
            $skill = Skill::create($data);
            return [
                'success' => true,
                'message' => 'Skill created successfully.',
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
