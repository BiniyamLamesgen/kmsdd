<?php

namespace App\Actions\Skill;

use App\Models\Skill;

class BulkDestroySkillAction
{
    public function execute(array $ids)
    {
        $count = Skill::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Skills deleted successfully.' : 'No skills deleted.',
        ];
    }
}
