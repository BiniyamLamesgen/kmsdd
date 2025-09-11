<?php

namespace App\Actions\EmployeeSkill;

use App\Models\EmployeeSkill;

class DestroyEmployeeSkillAction
{
    public function execute(string $id)
    {
        $employeeSkill = EmployeeSkill::find($id);
        if (!$employeeSkill) {
            return ['success' => false, 'message' => 'Employee skill not found.'];
        }
        $employeeSkill->delete();
        return ['success' => true, 'message' => 'Employee skill deleted successfully.'];
    }
}
