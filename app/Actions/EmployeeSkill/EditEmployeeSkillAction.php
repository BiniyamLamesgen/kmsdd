<?php

namespace App\Actions\EmployeeSkill;

use App\Models\EmployeeSkill;

class EditEmployeeSkillAction
{
    public function execute(string $id)
    {
        $employeeSkill = EmployeeSkill::with('employee', 'skill')->find($id);
        if (!$employeeSkill) {
            return ['success' => false, 'message' => 'Employee skill not found.'];
        }
        return ['success' => true, 'employeeSkill' => $employeeSkill];
    }
}
