<?php

namespace App\Actions\EmployeeSkill;

use App\Models\EmployeeSkill;

class BulkDestroyEmployeeSkillAction
{
    public function execute(array $ids)
    {
        $count = EmployeeSkill::whereIn('id', $ids)->delete();
        return [
            'success' => $count > 0,
            'message' => $count > 0 ? 'Employee skills deleted successfully.' : 'No employee skills deleted.',
        ];
    }
}
